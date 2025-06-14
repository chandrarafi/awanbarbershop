<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'otp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'otp_code', 'type', 'expired_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function generateOTP($userId, $type = 'registration')
    {
        // Generate 6 digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Set expiry time to 15 minutes from now
        $expiredAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Delete any existing OTP for this user and type
        $this->where('user_id', $userId)
            ->where('type', $type)
            ->delete();

        // Insert new OTP
        $this->insert([
            'user_id' => $userId,
            'otp_code' => $otp,
            'type' => $type,
            'expired_at' => $expiredAt
        ]);

        return $otp;
    }

    public function verifyOTP($userId, $otp, $type = 'registration')
    {
        $otpData = $this->where([
            'user_id' => $userId,
            'otp_code' => $otp,
            'type' => $type
        ])->first();

        if (!$otpData) {
            return false;
        }

        // Check if OTP is expired
        if (strtotime($otpData['expired_at']) < time()) {
            return false;
        }

        // Delete the used OTP
        $this->delete($otpData['id']);

        return true;
    }
}
