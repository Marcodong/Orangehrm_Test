<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software: you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with OrangeHRM.
 * If not, see <https://www.gnu.org/licenses/>.
 */

namespace OrangeHRM\Tests\Pim\Api\Model;

use DateTime;
use OrangeHRM\Core\Service\DateTimeHelperService;
use OrangeHRM\Entity\Employee;
use OrangeHRM\Entity\EmployeeLicense;
use OrangeHRM\Entity\License;
use OrangeHRM\Framework\Services;
use OrangeHRM\Pim\Api\Model\EmployeeLicenseModel;
use OrangeHRM\Tests\Util\KernelTestCase;

/**
 * @group Pim
 * @group Model
 */
class EmployeeLicenseModelTest extends KernelTestCase
{
    public function testToArray()
    {
        $resultArray = [
            'licenseNo' => '02',
            'issuedDate' => '2019-05-19',
            'expiryDate' => '2020-05-19',
            "license" => [
                "id" => 1,
                "name" => "CIMA"
            ]
        ];

        $employee = new Employee();
        $employee->setEmpNumber(1);
        $employee->setFirstName('First');
        $employee->setMiddleName('Middle');
        $employee->setLastName('Last');
        $employee->setEmployeeId('0001');
        $employee->setEmployeeTerminationRecord(null);

        $license = new License();
        $license->setId(1);
        $license->setName('CIMA');

        $employeeLicense = new EmployeeLicense();
        $employeeLicense->setEmployee($employee);
        $employeeLicense->setLicense($license);
        $employeeLicense->setLicenseNo('02');
        $employeeLicense->setLicenseIssuedDate(new DateTime('2019-05-19'));
        $employeeLicense->setLicenseExpiryDate(new DateTime('2020-05-19'));

        $employeeModel = new EmployeeLicenseModel($employeeLicense);
        $this->createKernelWithMockServices(
            [
                Services::DATETIME_HELPER_SERVICE => new DateTimeHelperService(),
            ]
        );
        $this->assertEquals($resultArray, $employeeModel->toArray());
    }
}
