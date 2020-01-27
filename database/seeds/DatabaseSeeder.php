<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call('SystemAdminRoleSeeder');

        $this->call('BusinessAdminRoleSeeder');

        $this->call('SystemSettingsSeeder');

        $this->call('ActivityTypeSeeder');

        $this->call('BusinessesSeeder');

        $this->call('OutletsSeeder');

        $this->call('BusinessAdminSeeder');

        $this->call('OutletAdminSeeder');

        $this->call('SystemAdminSeeder');

      

        $this->call('ActivityLogBusinessSeeder');

        $this->call('ActivityLogOutletSeeder');

        $this->call('ActivityLogSystemSeeder');

        
        $this->call('BizOutletMsgSeeder');

        $this->call('BizOutletMsgRecipientSeeder');

        $this->call('BizOutletMsgSeenSeeder');

        

        

        $this->call('CustomerBusinessSeeder');

        $this->call('BusinessStockSeeder');

        $this->call('BusinessSubscriptionSeeder');

        $this->call('BusinessSuppliersSeeder');

        $this->call('BusinessSupplySumSeeder');

        $this->call('BusinessSupplySeeder');

        
        $this->call('BusinessReceivingsSumSeeder');

        $this->call('BusinessReceivingsSeeder');

        

        

        

        $this->call('BusinessCustomerCreditSumSeeder');
        
        $this->call('BusinessCustomerCreditSeeder');

        $this->call('BusinessCreditPaymentSeeder');

        $this->call('BusinessPaymentResolutionSeeder');


        $this->call('CustomerOutletSeeder');

        

        $this->call('OutletStocksSeeder');

        $this->call('OutletSalesSumSeeder');

        $this->call('OutletSalesSeeder');

        $this->call('OutletCustomerCreditSumSeeder');

        $this->call('OutletCustomerCreditSeeder');

        $this->call('OutletReceivingsSumSeeder');

        $this->call('OutletReceivingsSeeder');

       


        $this->call('OutletCreditPaymentsSeeder');

       

        $this->call('SysBizMsgSeeder');

        $this->call('SysBizMsgRecipientSeeder');

        $this->call('SysBizMsgSeenSeeder');

        

        


    }
}
