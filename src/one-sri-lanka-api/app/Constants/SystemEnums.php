<?php

namespace App\Constants;

final class SystemEnums
{

    public const STATUS_ACTIVE   = 'active';
    public const STATUS_INACTIVE = 'inactive';

    // User Roles
    public const ROLE_ADMIN   = 'admin';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_USER    = 'user';

    // Complaint Types
    public const COMPLAINT_TYPE_TECHNICAL = 'technical';
    public const COMPLAINT_TYPE_SERVICE   = 'service';
    public const COMPLAINT_TYPE_BILLING   = 'billing';
}
