<?php


namespace App\Service;


class PakietManage
{

    public function validateBilingTime($pakiet_start, $settlement)
    {
        $date = new \DateTime($pakiet_start);
        $date->format('Y-m-d');


        if ($settlement == 1) {
            return $date = $this->MonthShifter($date, 1)->format(('Y-m-d')); //dodanie 1 miesiaca
        }
        if ($settlement == 2) {
            return $date = $this->MonthShifter($date, 3)->format(('Y-m-d')); //dodanie 3 miesiacy
        }
        if ($settlement == 3) {
            return $date = $this->MonthShifter($date, 6)->format(('Y-m-d')); //dodanie 6 miesiacy
        }
        if ($settlement == 4) {
            return $date = $this->MonthShifter($date, 12)->format(('Y-m-d')); //dodanie 12 miesiacy
        }

        return false;
    }

    public function MonthShifter(\DateTime $aDate, $months)
    {
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Month'));
        if ($dateB != $dateA->modify($months*-1 . ' Month')) {
            $result = $plusMonths->modify('last day of last month');
        } elseif ($aDate == $dateB->modify('last day of this month')) {
            $result =  $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
            $plusMonths = $plusMonths->format('Y-m-d');
            if ($plusMonths[8]=="0"&&$plusMonths[9]=="1") {
                $result = new \DateTime($plusMonths);
                $result->modify('-1 day');
            }
        }
        return $result;
    }

}