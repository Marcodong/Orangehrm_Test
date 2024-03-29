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

namespace OrangeHRM\Tests\Authentication\Api;

use OrangeHRM\Authentication\PublicApi\PasswordStrengthValidationAPI;
use OrangeHRM\Config\Config;
use OrangeHRM\Core\Service\CacheService;
use OrangeHRM\Framework\ServiceContainer;
use OrangeHRM\Framework\Services;
use OrangeHRM\Tests\Util\EndpointIntegrationTestCase;
use OrangeHRM\Tests\Util\Integration\TestCaseParams;
use OrangeHRM\Tests\Util\TestDataService;

/**
 * @group Authentication
 * @group APIv2
 */
class PasswordStrengthValidationAPITest extends EndpointIntegrationTestCase
{
    public static function setUpBeforeClass(): void
    {
        ServiceContainer::getContainer()->register(Services::CACHE)->setFactory([CacheService::class, 'getCache']);
        ServiceContainer::getContainer()->get(Services::CACHE)->clear('core.i18n');
        TestDataService::populate(Config::get(Config::TEST_DIR) . '/phpunit/fixtures/LangString.yaml', true);
    }

    /**
     * @dataProvider dataProviderForTestCreate
     */
    public function testCreate(TestCaseParams $testCaseParams): void
    {
        $this->populateFixtures('PasswordStrengthValidation.yml', null, true);

        $this->createKernelWithMockServices([Services::AUTH_USER => $this->getMockAuthUser($testCaseParams)]);
        $this->registerServices($testCaseParams);
        $api = $this->getApiEndpointMock(PasswordStrengthValidationAPI::class, $testCaseParams);
        $this->assertValidTestCase($api, 'create', $testCaseParams);
    }

    public function dataProviderForTestCreate(): array
    {
        return $this->getTestCases('PasswordStrengthValidationAPITestCases.yaml', 'Create');
    }

    public function testDelete(): void
    {
        $api = new PasswordStrengthValidationAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->delete();
    }

    public function testGetValidationRuleForDelete(): void
    {
        $api = new PasswordStrengthValidationAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->getValidationRuleForDelete();
    }

    public function testGetAll(): void
    {
        $api = new PasswordStrengthValidationAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->getAll();
    }

    public function testGetValidationRuleForGetAll(): void
    {
        $api = new PasswordStrengthValidationAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->getValidationRuleForGetAll();
    }
}
