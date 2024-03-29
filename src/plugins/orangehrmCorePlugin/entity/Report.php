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

namespace OrangeHRM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ohrm_report")
 * @ORM\Entity
 */
class Report
{
    /**
     * @var int
     *
     * @ORM\Column(name="report_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private string $name;

    /**
     * @var ReportGroup
     *
     * @ORM\ManyToOne(targetEntity="OrangeHRM\Entity\ReportGroup")
     * @ORM\JoinColumn(name="report_group_id", referencedColumnName="report_group_id")
     */
    private ReportGroup $reportGroup;

    /**
     * @var bool
     *
     * @ORM\Column(name="use_filter_field", type="boolean")
     */
    private bool $useFilterField;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private ?string $type = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ReportGroup
     */
    public function getReportGroup(): ReportGroup
    {
        return $this->reportGroup;
    }

    /**
     * @param ReportGroup $reportGroup
     */
    public function setReportGroup(ReportGroup $reportGroup): void
    {
        $this->reportGroup = $reportGroup;
    }

    /**
     * @return bool
     */
    public function isUseFilterField(): bool
    {
        return $this->useFilterField;
    }

    /**
     * @param bool $useFilterField
     */
    public function setUseFilterField(bool $useFilterField): void
    {
        $this->useFilterField = $useFilterField;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }
}
