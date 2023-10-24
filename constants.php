<?php

const STATUSES = [
    0 => 'Completed',
    1 => 'Pending',
    2 => 'Rejected',
];

const NOTIFICATION_EVENT_CHANGE_RETURN_STATUS = 'changeReturnStatus';
const NOTIFICATION_EVENT_NEW_RETURN_STATUS = 'newReturnStatus';
const NOTIFICATION_TYPE_NEW = 1;
const NOTIFICATION_TYPE_CHANGE = 2;

const PERSON_TYPE_CUSTOMER = 0;