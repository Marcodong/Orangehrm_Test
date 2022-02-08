<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace OrangeHRM\Time\Report;

use OrangeHRM\Core\Api\V2\Exception\ForbiddenException;
use OrangeHRM\Core\Api\V2\RequestParams;
use OrangeHRM\Core\Api\V2\Validator\ParamRule;
use OrangeHRM\Core\Api\V2\Validator\ParamRuleCollection;
use OrangeHRM\Core\Api\V2\Validator\Rule;
use OrangeHRM\Core\Api\V2\Validator\Rules;
use OrangeHRM\Core\Dto\FilterParams;
use OrangeHRM\Core\Report\Api\EndpointAwareReport;
use OrangeHRM\Core\Report\Api\EndpointProxy;
use OrangeHRM\Core\Report\Filter\Filter;
use OrangeHRM\Core\Report\Filter\FilterDefinition;
use OrangeHRM\Core\Report\Header\Column;
use OrangeHRM\Core\Report\Header\Header;
use OrangeHRM\Core\Report\Header\HeaderDefinition;
use OrangeHRM\Core\Report\ReportData;
use OrangeHRM\Core\Traits\Service\TextHelperTrait;
use OrangeHRM\Core\Traits\UserRoleManagerTrait;
use OrangeHRM\Entity\Employee;
use OrangeHRM\Time\Dto\AttendanceReportSearchFilterParams;

class AttendanceReport implements EndpointAwareReport
{
    use UserRoleManagerTrait;
    use TextHelperTrait;

    public const PARAMETER_EMPLOYEE_NAME = 'employeeName';
    public const PARAMETER_TIME = 'time';

    public const FILTER_EMP_NUMBER = 'empNumber';
    public const FILTER_JOB_TITLE_ID = 'jobTitleId';
    public const FILTER_SUBUNIT_ID = 'subunitId';
    public const FILTER_EMPLOYMENT_STATUS_ID = 'employmentStatusId';
    public const FILTER_PARAMETER_DATE_FROM = 'fromDate';
    public const FILTER_PARAMETER_DATE_TO = 'toDate';


    public const DEFAULT_COLUMN_SIZE = 150;

    /**
     * @inheritDoc
     */
    public function prepareFilterParams(EndpointProxy $endpoint): FilterParams
    {
        $filterParams = new AttendanceReportSearchFilterParams();

        $endpoint->setSortingAndPaginationParams($filterParams);

        $empNumber = $endpoint->getRequestParams()->getIntOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            self::FILTER_EMP_NUMBER
        );

        if (!is_null($empNumber)) {
            $filterParams->setEmployeeNumbers([$empNumber]);
        } else {
            $accessibleEmpNumbers = $this->getUserRoleManager()->getAccessibleEntityIds(Employee::class);
            $filterParams->setEmployeeNumbers($accessibleEmpNumbers);
        }

        $filterParams->setJobTitleId(
            $endpoint->getRequestParams()->getIntOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_JOB_TITLE_ID
            )
        );
        $filterParams->setSubunitId(
            $endpoint->getRequestParams()->getIntOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_SUBUNIT_ID
            )
        );
        $filterParams->setEmploymentStatusId(
            $endpoint->getRequestParams()->getIntOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_EMPLOYMENT_STATUS_ID
            )
        );
        $filterParams->setFromDate(
            $endpoint->getRequestParams()->getDateTimeOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_PARAMETER_DATE_FROM
            )
        );
        $filterParams->setToDate(
            $endpoint->getRequestParams()->getDateTimeOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_PARAMETER_DATE_TO
            )
        );

        return $filterParams;
    }

    /**
     * @inheritDoc
     */
    public function getValidationRule(EndpointProxy $endpoint): ParamRuleCollection
    {
        return new ParamRuleCollection(
            $endpoint->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::FILTER_EMP_NUMBER,
                    new Rule(Rules::IN_ACCESSIBLE_EMP_NUMBERS)
                )
            ),
            $endpoint->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(self::FILTER_JOB_TITLE_ID, new Rule(Rules::POSITIVE))
            ),
            $endpoint->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(self::FILTER_SUBUNIT_ID, new Rule(Rules::POSITIVE))
            ),
            $endpoint->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(self::FILTER_EMPLOYMENT_STATUS_ID, new Rule(Rules::POSITIVE))
            ),
            $endpoint->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(self::FILTER_PARAMETER_DATE_FROM, new Rule(Rules::API_DATE))
            ),
            $endpoint->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::FILTER_PARAMETER_DATE_TO,
                    new Rule(Rules::API_DATE),
                    new Rule(Rules::CALLBACK, [
                        function () use ($endpoint) {
                            $fromDate = $endpoint->getRequestParams()->getDateTimeOrNull(
                                RequestParams::PARAM_TYPE_QUERY,
                                self::FILTER_PARAMETER_DATE_FROM
                            );

                            $toDate = $endpoint->getRequestParams()->getDateTimeOrNull(
                                RequestParams::PARAM_TYPE_QUERY,
                                self::FILTER_PARAMETER_DATE_TO
                            );

                            if (!is_null($fromDate) && !is_null($toDate)) {
                                if ($fromDate > $toDate) {
                                    return false;
                                }
                            }
                            return true;
                        }
                    ])
                )
            ),
            ...$endpoint->getSortingAndPaginationParamsRules(
                AttendanceReportSearchFilterParams::ALLOWED_SORT_FIELDS
            )
        );
    }

    /**
     * @return Header
     */
    public function getHeaderDefinition(): HeaderDefinition
    {
        return new Header(
            [
                (new Column(self::PARAMETER_EMPLOYEE_NAME))->setName('Employee Name')
                    ->setSize(self::DEFAULT_COLUMN_SIZE),
                (new Column(self::PARAMETER_TIME))->setName('Time (Hours)')
                    ->setCellProperties(['class' => ['col-alt' => true]])
                    ->setSize(self::DEFAULT_COLUMN_SIZE),
            ]
        );
    }

    /**
     * @return Filter
     */
    public function getFilterDefinition(): FilterDefinition
    {
        return new Filter();
    }

    /**
     * @param AttendanceReportSearchFilterParams $filterParams
     * @return AttendanceReportData
     */
    public function getData(FilterParams $filterParams): ReportData
    {
        return new AttendanceReportData($filterParams);
    }

    /**
     * @inheritDoc
     */
    public function checkReportAccessibility(EndpointProxy $endpoint): void
    {
        if (!$this->getUserRoleManagerHelper()
            ->getEntityIndependentDataGroupPermissions('attendance_summary')->canRead()) {
            throw new ForbiddenException();
        }
    }
}