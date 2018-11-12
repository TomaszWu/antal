<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CreateFullReport
{
    const _REPORTS_DIR_ = 'files/reports';

    const _VALID_EMAILS_FILE_NAME_ = 'validEmails.csv';

    const _INVALID_EMAILS_FILE_NAME_ = 'invalidEmails.csv';

    const _MODE_ = 'w+';

    /**
     * @var GroupEmailsIfValidOrNot $groupEmailIfValidOrNot
     */
    private $groupEmailIfValidOrNot;

    /**
     * @var CreatePath $createPath
     */
    private $createPath;

    /**
     * @var SaveArrayContentToFile $saveArrayToPath
     */
    private $saveArrayToPath;

    /**
     * @var SummaryReportContent $summaryReport
     */
    private $summaryReport;

    /**
     * @var ParameterBagInterface $params
     */
    private $params;

    /**
     * CreateFullReport constructor.
     * @param GroupEmailsIfValidOrNot $groupEmailIfValidOrNot
     * @param CreatePath $createPath
     * @param SaveArrayContentToFile $saveArrayToPath
     * @param SummaryReportContent $summaryReport
     * @param ParameterBagInterface $params
     */
    public function __construct(
        GroupEmailsIfValidOrNot $groupEmailIfValidOrNot,
        CreatePath $createPath, SaveArrayContentToFile $saveArrayToPath,
        SummaryReportContent $summaryReport,
        ParameterBagInterface $params
    ) {
        $this->groupEmailIfValidOrNot = $groupEmailIfValidOrNot;
        $this->createPath = $createPath;
        $this->saveArrayToPath = $saveArrayToPath;
        $this->summaryReport = $summaryReport;
        $this->params = $params;
    }

    public function create(array $emails): bool
    {
        $reportsDir = sprintf('%s/%s', $this->params->get('kernel.project_dir'), self::_REPORTS_DIR_);

        if (!file_exists(sprintf('%s/%s', $this->params->get('kernel.project_dir'), self::_REPORTS_DIR_))) {
            $this->createPath->create(self::_REPORTS_DIR_);
        }

        $emailsGrouped = $this->groupEmailIfValidOrNot->group($emails);

        $this->saveArrayToPath->save($emailsGrouped['validEmails'], $reportsDir, self::_VALID_EMAILS_FILE_NAME_, self::_MODE_);
        $this->saveArrayToPath->save($emailsGrouped['invalidEmails'], $reportsDir, self::_INVALID_EMAILS_FILE_NAME_, self::_MODE_);

        $summary = $this->summaryReport->createSummary(count($emailsGrouped['validEmails']), count($emailsGrouped['invalidEmails']));

        return $this->saveArrayToPath->save([$summary], $reportsDir, 'summary.csv', 'w+');
    }
}
