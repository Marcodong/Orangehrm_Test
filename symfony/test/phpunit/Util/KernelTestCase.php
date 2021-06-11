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

namespace OrangeHRM\Tests\Util;

use OrangeHRM\Core\Traits\ServiceContainerTrait;
use OrangeHRM\Framework\Framework;
use OrangeHRM\Framework\Http\Request;

abstract class KernelTestCase extends TestCase
{
    use ServiceContainerTrait;

    /**
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @return Request
     */
    protected function getHttpRequest(array $query = [], array $request = [], array $attributes = []): Request
    {
        return new Request($query, $request, $attributes);
    }

    /**
     * @return Framework
     */
    protected function createKernel(): Framework
    {
        $this->getContainer()->reset();
        return $this->getMockBuilder(Framework::class)
            ->onlyMethods(['handle'])
            ->setConstructorArgs(['test', true])
            ->getMock();
    }

    /**
     * @param array $services
     * @return Framework
     */
    protected function createKernelWithMockServices(array $services = []): Framework
    {
        $kernel = $this->createKernel();

        foreach ($services as $id => $service) {
            $this->getContainer()->set($id, $service);
        }
        return $kernel;
    }
}