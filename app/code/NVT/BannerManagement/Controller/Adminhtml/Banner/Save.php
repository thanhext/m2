<?php
namespace NVT\BannerManagement\Controller\Adminhtml\Banner;
/**
 * Class Save
 * @package NVT\BannerManagement\Controller\Adminhtml\Banner
 */
class Save extends \NVT\BannerManagement\Controller\Adminhtml\Banner
{
    public function execute()
    {
        $isPost = $this->getRequest()->getPost();
        $data = $this->getRequest()->getParam('banner');
        if ($isPost) {
            $model = $this->_bannerFactory->create();
            $model->setData($data);
            try {
                // Save news
                $model->save();
                // Display success message
                $this->messageManager->addSuccess(__('The Banner has been saved.'));
                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getBannerId(), '_current' => true]);
                    return;
                }
                // Go to grid page
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/index', ['id' => $model->getBannerId()]);
        }
    }

}
