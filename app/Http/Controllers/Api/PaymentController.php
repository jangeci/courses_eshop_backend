<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use Carbon\Carbon;
use http\Exception\UnexpectedValueException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        try {
            $courseId = $request->id;
            $user = $request->user();
            $token = $user->token;

            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $course = Course::where('id', $courseId)->first();
            if (empty($course)) {
                return response()->json(
                    [
                        'code' => 204,
                        'msg' => 'No course found',
                        'data' => ''
                    ]
                );
            }

            $orderMap = [];
            $orderMap["course_id"] = $courseId;
            $orderMap["user_token"] = $token;
            /**
             * Status 1 - successful order
             * */
            $orderMap["status"] = 1;

            $orderRes = Order::where($orderMap)->first();

            /**
             * Order for this item has already been placed before
             * */
            if (!empty($orderRes)) {
                return response()->json(
                    [
                        'code' => 409, //conflict
                        'msg' => 'Order already exists',
                        'data' => ''
                    ]
                );
            }

            $our_domain = env('APP_URL');
            $map = [];
            $map["user_token"] = $token;
            $map["course_id"] = $courseId;
            $map["total_amount"] = $course->price;
            $map["status"] = 0;
            $map["created_at"] = Carbon::now();

            $orderNum = Order::insertGetId($map);

            $checkoutSession = Session::create(
                [
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => 'USD',
                                'product_data' => [
                                    'name' => $course->name,
                                    'description' => $course->description,
                                ],
                                'unit_amount' => intval($course->price * 100), // Convert price to cents
                            ],
                            'quantity' => 1, // Specify quantity here
                        ],
                    ],
                    'payment_intent_data' => [
                        'metadata' => [
                            'order_num' => $orderNum,
                            'user_token' => $token,
                        ],
                    ],
                    'metadata' => [
                        'order_num' => $orderNum,
                        'user_token' => $token,
                    ],
                    'mode' => 'payment',
                    'success_url' => $our_domain . 'payment/success/' . $orderNum,
                    'cancel_url' => $our_domain . 'payment/cancel/' . $orderNum,
                ]
            );

            return response()->json(
                [
                    'code' => 200,
                    'msg' => 'Order has been placed',
                    'data' => $checkoutSession->url,
                ]
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'code' => 500,
                    'status' => false,
                    'message' => $exception->getMessage(),
                ], 500
            );
        }
    }

    public function webGoHooks(Request $request)
    {
        Log::info('start here.....');

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $endpointSecret = env('STRIPE_SIGNING_SECRET');
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;
        Log::info('set up buffer and handshake done....');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::info('Unexpected value exception' . $e);
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::info('SignatureVerificationException ' . $e);
        }

        if($event->type == 'charge.succeeded') {
            $session = $event->data->object;
            $metadata = $session->metadata;
            $orderNum = $metadata->order_num;
            $userToken = $metadata->user_token;
            Log::info('order id is ' . $orderNum);

            $map = [];
            $map['status'] = 1;
            $map['updated_at'] = Carbon::now();
            $whereMap = [];
            $whereMap['user_token'] = $userToken;
            $whereMap['id'] = $orderNum;

            Order::where($whereMap)->update($map);
        }

        http_response_code(200);
    }
}
