<?php

function formatCurrency($value, $encoding = true): string {
    $curr = CURRENCY;

    if ($encoding) {
        $curr = (ord(CURRENCY) == "128") ? "&#8364;" : htmlentities(CURRENCY);
    } 

    return $curr . number_format($value, 2, ".", ",");
}