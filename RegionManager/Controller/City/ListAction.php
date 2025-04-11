<?php
/**
 * City List Controller
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Controller\City;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use MagoArab\RegionManager\Api\CityRepositoryInterface;

class ListAction implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * @param RequestInterface $request
     * @param JsonFactory $resultJsonFactory
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(
        RequestInterface $request,
        JsonFactory $resultJsonFactory,
        CityRepositoryInterface $cityRepository
    ) {
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->cityRepository = $cityRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        
        $regionId = $this->getRegionId();
        if (!$regionId) {
            return $resultJson->setData([]);
        }
        
        try {
            $searchResult = $this->cityRepository->getByRegionId($regionId);
            $cities = [];
            
            foreach ($searchResult->getItems() as $city) {
                $cities[] = [
                    'city_id' => $city->getCityId(),
                    'region_id' => $city->getRegionId(),
                    'code' => $city->getCode(),
                    'default_name' => $city->getDefaultName()
                ];
            }
            
            return $resultJson->setData($cities);
        } catch (\Exception $e) {
            return $resultJson->setData([]);
        }
    }

    /**
     * Get region ID from request
     *
     * @return int|null
     */
    private function getRegionId()
    {
        $params = json_decode($this->request->getContent(), true);
        return isset($params['regionId']) ? (int)$params['regionId'] : null;
    }
}