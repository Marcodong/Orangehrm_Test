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

namespace OrangeHRM\Time\Api;

use OrangeHRM\Core\Api\V2\Endpoint;
use OrangeHRM\Core\Api\V2\EndpointResourceResult;
use OrangeHRM\Core\Api\V2\EndpointResult;
use OrangeHRM\Core\Api\V2\Model\ArrayModel;
use OrangeHRM\Core\Api\V2\RequestParams;
use OrangeHRM\Core\Api\V2\ResourceEndpoint;
use OrangeHRM\Core\Api\V2\Validator\ParamRule;
use OrangeHRM\Core\Api\V2\Validator\ParamRuleCollection;
use OrangeHRM\Core\Api\V2\Validator\Rule;
use OrangeHRM\Core\Api\V2\Validator\Rules;
use OrangeHRM\Entity\Customer;
use OrangeHRM\Time\Traits\Service\CustomerServiceTrait;

class ValidationCustomerNameAPI extends Endpoint implements ResourceEndpoint
{
    use CustomerServiceTrait;

    public const PARAMETER_CUSTOMER_NAME = 'customerName';
    public const PARAMETER_CUSTOMER_Id = 'customerId';
    public const PARAMETER_IS_CHANGEABLE_CUSTOMER_NAME = 'valid';

    public const PARAM_RULE_CUSTOMER_NAME_MAX_LENGTH = 50;

    /**
     * @OA\Get(
     *     path="/api/v2/time/validation/customer-name",
     *     tags={"Time/Validation"},
     *     summary="Validate Customer Name Uniqueness",
     *     operationId="validate-customer-name-uniqueness",
     *     @OA\Parameter(
     *         name="customerId",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="customerName",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             maxLength=OrangeHRM\Time\Api\ValidationCustomerNameAPI::PARAM_RULE_CUSTOMER_NAME_MAX_LENGTH
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="valid", type="boolean"),
     *             ),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response="404", ref="#/components/responses/RecordNotFound")
     * )
     *
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $customerName = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_CUSTOMER_NAME
        );
        $customerId = $this->getRequestParams()->getIntOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_CUSTOMER_Id
        );
        if (!is_null($customerId)) {
            $user = $this->getCustomerService()->getCustomerDao()->getCustomer($customerId);
            $this->throwRecordNotFoundExceptionIfNotExist($user, Customer::class);
        }
        $isChangeableCustomerName = !$this->getCustomerService()
            ->getCustomerDao()
            ->isCustomerNameTaken($customerName, $customerId);
        return new EndpointResourceResult(
            ArrayModel::class,
            [
                self::PARAMETER_IS_CHANGEABLE_CUSTOMER_NAME => $isChangeableCustomerName,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_CUSTOMER_NAME,
                new Rule(Rules::STRING_TYPE),
                new Rule(Rules::LENGTH, [null, self::PARAM_RULE_CUSTOMER_NAME_MAX_LENGTH]),
            ),
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::PARAMETER_CUSTOMER_Id,
                    new Rule(Rules::POSITIVE),
                )
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function delete(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }
}
