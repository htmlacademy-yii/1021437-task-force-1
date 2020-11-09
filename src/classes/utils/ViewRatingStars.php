<?php


namespace Task\classes\utils;


class ViewRatingStars
{
    public static function getRating($totalStar)
    {
        $templateRating = '';
        for ($i = 1; $i <= 5; $i++) {
            $templateRating .= ($totalStar >= $i) ? '<span></span>' : '<span class="star-disabled"></span>';
        }

        return $templateRating;
    }

}
