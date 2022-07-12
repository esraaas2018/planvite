<?php

function apiResponse($data = null, $message = null, $status = 200)
{
    return response()->json([
        'data' => $data,
        'message' => $message
    ],$status);
}
