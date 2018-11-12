<?php

namespace App\Service;

class SummaryReportContent
{

    public function createSummary(int $validEmailsCount, int $invalidEmailsCount)
    {
        $fileContent = null;

        $this->validateGivenInts($validEmailsCount, $invalidEmailsCount);

        $allEmailsCount = $validEmailsCount + $invalidEmailsCount;

        if (0 == $invalidEmailsCount && $validEmailsCount) {
            return $fileContent = sprintf('Total emails number: %s all of them valid', $allEmailsCount);
        }

        if ($validEmailsCount == 0 && $invalidEmailsCount) {
            return $fileContent = sprintf('Total emails number: %s none of them valid', $allEmailsCount);
        }

        $validEmailsPercentage = round(($validEmailsCount / $allEmailsCount) * 100, 2);
        $invalidEmailsPercentage = round(($invalidEmailsCount / $allEmailsCount) * 100, 2);

        return sprintf(
            "Total emails number: %d. Number of valid ones: %d (%d%%), invalid ones: %d (%d%%).",
            $allEmailsCount,
            $validEmailsCount,
            $validEmailsPercentage,
            $invalidEmailsCount,
            $invalidEmailsPercentage
            );
    }

    private function validateGivenInts(int $validEmailsCount, int $invalidEmailsCount)
    {
        if (0 > $validEmailsCount || 0 > $invalidEmailsCount) {
            throw new \InvalidArgumentException('uupps, negative emails count');
        }
    }
}
