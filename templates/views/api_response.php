<?php
echo json_encode([
    'status' => $this->status,
    'data' => [
        'message' => $this->message
    ]
]);
