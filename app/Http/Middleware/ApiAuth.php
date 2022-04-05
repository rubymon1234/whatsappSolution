<?php

namespace App\Http\Middleware;
use Closure;
use App\Models\Api;
use Illuminate\Support\Str;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiTokenValidate = false;
        $apiToken = $this->bearerToken($request);
        if (isset($apiToken)) {
            $apiTokenValidate = Api::where('is_status',1)->where('api_key',$apiToken)->first();
        }
        if($apiTokenValidate){
            return $next($request);
        }else{
            return response()->json([
                'status' => 'FAILED',
                'data' => [
                  'status' => 'failed',
                  'message' => 'Invalid API key'
                ]
            ]);
        }
    }
    /**
     * Handle an incoming request Token Verify.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function bearerToken($request)
    {
       $header = $request->header('Authorization', '');
       if (Str::startsWith($header, 'Bearer ')) {
                return Str::substr($header, 7);
       }
    }
}
