<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 07/05/16
 * Time: 16:45
 */

namespace AppBundle\Command;

/**
 * Trait DateVerificationTrait
 * @package AppBundle\Command
 */
trait DateVerificationTrait
{
    /**
     * Verifies start date
     * @param string|null $startDate
     * @return string
     */
    private function verifyStartDate($startDate): string
    {
        if ((null === $startDate) || (!$this->validateDate($startDate))) {
            // Set date to yesterday
            return date('Y-m-d', strtotime("-1 days"));
        }
        return $startDate;
    }

    /**
     * Verifies end date
     * @param string|null $endDate
     * @return string
     */
    private function verifyEndDate($endDate): string
    {
        if ((null === $endDate) || (!$this->validateDate($endDate))) {
            // Set date to current date
            return date('Y-m-d');
        }
        return $endDate;
    }

    /**
     * Validates date correctness
     * @param string $date
     * @param string $format
     * @return bool
     */
    private function validateDate(string $date, string $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}