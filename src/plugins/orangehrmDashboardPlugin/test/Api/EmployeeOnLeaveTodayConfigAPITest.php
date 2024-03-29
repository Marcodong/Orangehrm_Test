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

namespace OrangeHRM\Tests\Dashboard\Api;

use OrangeHRM\Core\Service\ConfigService;
use OrangeHRM\Dashboard\Api\EmployeeOnLeaveTodayConfigAPI;
use OrangeHRM\Entity\Config;
use OrangeHRM\Framework\Services;
use OrangeHRM\ORM\Doctrine;
use OrangeHRM\Tests\Util\EndpointIntegrationTestCase;
use OrangeHRM\Tests\Util\Integration\TestCaseParams;

/**
 * @group Dashboard
 * @group APIv2
 */
class EmployeeOnLeaveTodayConfigAPITest extends EndpointIntegrationTestCase
{
    /**
     * @dataProvider dataProviderForTestGetOne
     */
    public function testGetOne(TestCaseParams $testCaseParams): void
    {
        $this->populateFixtures('EmployeeOnLeaveTodayConfig.yml', null, true);
        $this->createKernelWithMockServices([Services::AUTH_USER => $this->getMockAuthUser($testCaseParams)]);
        $this->registerServices($testCaseParams);
        $this->registerMockDateTimeHelper($testCaseParams);
        $api = $this->getApiEndpointMock(EmployeeOnLeaveTodayConfigAPI::class, $testCaseParams);
        $this->assertValidTestCase($api, 'getOne', $testCaseParams);
    }

    public function dataProviderForTestGetOne(): array
    {
        return $this->getTestCases('EmployeeOnLeaveTodayConfigAPITestCases.yaml', 'GetOne');
    }

    public static function configEnablingPreHook(TestCaseParams $testCaseParams)
    {
        $config = Doctrine::getEntityManager()->getRepository(Config::class)->find(
            ConfigService::KEY_DASHBOARD_EMPLOYEES_ON_LEAVE_TODAY_SHOW_ONLY_ACCESSIBLE
        );
        $config->setValue(1);
        Doctrine::getEntityManager()->persist($config);
        Doctrine::getEntityManager()->flush($config);
    }

    /**
     * @dataProvider dataProviderForTestUpdate
     */
    public function testUpdate(TestCaseParams $testCaseParams): void
    {
        $this->populateFixtures('EmployeeOnLeaveTodayConfig.yml', null, true);
        $this->createKernelWithMockServices([Services::AUTH_USER => $this->getMockAuthUser($testCaseParams)]);
        $this->registerServices($testCaseParams);
        $this->registerMockDateTimeHelper($testCaseParams);
        $api = $this->getApiEndpointMock(EmployeeOnLeaveTodayConfigAPI::class, $testCaseParams);
        $this->assertValidTestCase($api, 'update', $testCaseParams);
    }

    public function dataProviderForTestUpdate(): array
    {
        return $this->getTestCases('EmployeeOnLeaveTodayConfigAPITestCases.yaml', 'Update');
    }

    public function testDelete(): void
    {
        $api = new EmployeeOnLeaveTodayConfigAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->delete();
    }

    public function testGetValidationRuleForDelete(): void
    {
        $api = new EmployeeOnLeaveTodayConfigAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->getValidationRuleForDelete();
    }
}
