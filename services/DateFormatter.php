<?php

class DateFormatter
{
    public static function formatMemberSince(DateTime $createdAt): string
    {
        $now = new DateTime();
        $diff = $createdAt->diff($now);

        if ($diff->y > 0) {
            return $diff->y . ' ' . ($diff->y > 1 ? 'ans' : 'an') . ($diff->m > 0 ? ' et ' . $diff->m . ' mois' : '');
        } elseif ($diff->m > 0) {
            return $diff->m . ' mois';
        } elseif ($diff->d > 0) {
            return $diff->d . ' ' . ($diff->d > 1 ? 'jours' : 'jour');
        }
        return '1 jour';
    }
}
