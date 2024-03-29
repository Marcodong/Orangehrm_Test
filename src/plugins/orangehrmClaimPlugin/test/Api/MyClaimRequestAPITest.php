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

namespace OrangeHRM\Tests\Claim\Api;

use OrangeHRM\Claim\Api\MyClaimRequestAPI;
use OrangeHRM\Config\Config;
use OrangeHRM\Framework\Services;
use OrangeHRM\Tests\Util\EndpointIntegrationTestCase;
use OrangeHRM\Tests\Util\Integration\TestCaseParams;
use OrangeHRM\Tests\Util\TestDataService;

/**
 * @group Claim
 * @group APIv2
 */
class MyClaimRequestAPITest extends EndpointIntegrationTestCase
{
    public static function setUpBeforeClass(): void
    {
        TestDataService::populate(
            Config::get(Config::TEST_DIR) . '/phpunit/fixtures/WorkflowStateMachine.yaml',
            true
        );
    }

    /**
     * @dataProvider dataProviderForTestCreate
     */
    public function testCreate(TestCaseParams $testCaseParams): void
    {
        $this->populateFixtures('MyClaimRequestAPITest.yaml', null, true);
        $this->createKernelWithMockServices([Services::AUTH_USER => $this->getMockAuthUser($testCaseParams)]);
        $this->registerMockDateTimeHelper($testCaseParams);
        $this->registerServices($testCaseParams);
        $api = $this->getApiEndpointMock(MyClaimRequestAPI::class, $testCaseParams);
        $this->assertValidTestCase($api, 'create', $testCaseParams);
    }

    public function dataProviderForTestCreate(): array
    {
        return $this->getTestCases('MyClaimRequestAPITestCases.yaml', 'Create');
    }

    /**
     * @dataProvider dataProviderForTestGetOne
     */
    public function testGetOne(TestCaseParams $testCaseParams): void
    {
        $this->populateFixtures('MyClaimRequestAPITest.yaml', null, true);
        $this->createKernelWithMockServices([
            Services::AUTH_USER => $this->getMockAuthUser($testCaseParams)
        ]);
        $this->registerServices($testCaseParams);
        $api = $this->getApiEndpointMock(MyClaimRequestAPI::class, $testCaseParams);
        $this->assertValidTestCase($api, 'getOne', $testCaseParams);
    }

    public function dataProviderForTestGetOne(): array
    {
        return $this->getTestCases('MyClaimRequestAPITestCases.yaml', 'GetOne');
    }

    /**
     * @dataProvider dataProviderForTestGetAll
     */
    public function testGetAll(TestCaseParams $testCaseParams): void
    {
        $this->populateFixtures('MyClaimRequestAPITest.yaml', null, true);
        $this->createKernelWithMockServices([
            Services::AUTH_USER => $this->getMockAuthUser($testCaseParams)
        ]);
        $this->registerServices($testCaseParams);
        $api = $this->getApiEndpointMock(MyClaimRequestAPI::class, $testCaseParams);
        $this->assertValidTestCase($api, 'getAll', $testCaseParams);
    }

    public function dataProviderForTestGetAll(): array
    {
        return $this->getTestCases('MyClaimRequestAPITestCases.yaml', 'GetAll');
    }
}
