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

namespace OrangeHRM\Tests\Admin\Dao;

use OrangeHRM\Admin\Dao\JobCategoryDao;
use OrangeHRM\Config\Config;
use OrangeHRM\Tests\Util\TestCase;
use OrangeHRM\Tests\Util\TestDataService;
use Exception;

/**
 * @group Admin
 * @group Dao
 */
class JobCategoryDaoTest extends TestCase
{
    private JobCategoryDao $jobCatDao;
    protected string $fixture;

    /**
     * Set up method
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->jobCatDao = new JobCategoryDao();
        $this->fixture = Config::get(Config::PLUGINS_DIR) . '/orangehrmAdminPlugin/test/fixtures/JobCategoryDao.yml';
        TestDataService::populate($this->fixture);
    }

    public function testGetJobCategoryList(): void
    {
        $result = $this->jobCatDao->getJobCategoryList();
        $this->assertEquals(count($result), 3);
    }

    public function testGetJobCategoryById(): void
    {
        $result = $this->jobCatDao->getJobCategoryById(1);
        $this->assertEquals($result->getName(), 'Job Category 1');
    }
}
