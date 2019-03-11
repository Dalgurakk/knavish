<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetEngineInnodb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE car_brands ENGINE=InnoDB;');
        DB::statement('ALTER TABLE car_categories ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_board_types ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_contract_board_type ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_contract_market ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_contract_measure ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_contract_pax_type ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_contract_room_type ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_contract_settings ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_contracts ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_hotels_chain ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_images ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_measures ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_pax_types ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotel_room_types ENGINE=InnoDB;');
        DB::statement('ALTER TABLE hotels ENGINE=InnoDB;');
        DB::statement('ALTER TABLE locations ENGINE=InnoDB;');
        DB::statement('ALTER TABLE markets ENGINE=InnoDB;');
        DB::statement('ALTER TABLE migrations ENGINE=InnoDB;');
        DB::statement('ALTER TABLE password_resets ENGINE=InnoDB;');
        DB::statement('ALTER TABLE role_user ENGINE=InnoDB;');
        DB::statement('ALTER TABLE roles ENGINE=InnoDB;');
        DB::statement('ALTER TABLE users ENGINE=InnoDB;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE car_brands ENGINE=MyISAM;');
        DB::statement('ALTER TABLE car_categories ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_board_types ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_contract_board_type ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_contract_market ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_contract_measure ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_contract_pax_type ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_contract_room_type ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_contract_settings ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_contracts ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_hotels_chain ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_images ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_measures ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_pax_types ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotel_room_types ENGINE=MyISAM;');
        DB::statement('ALTER TABLE hotels ENGINE=MyISAM;');
        DB::statement('ALTER TABLE locations ENGINE=MyISAM;');
        DB::statement('ALTER TABLE markets ENGINE=MyISAM;');
        DB::statement('ALTER TABLE migrations ENGINE=MyISAM;');
        DB::statement('ALTER TABLE password_resets ENGINE=MyISAM;');
        DB::statement('ALTER TABLE role_user ENGINE=MyISAM;');
        DB::statement('ALTER TABLE roles ENGINE=MyISAM;');
        DB::statement('ALTER TABLE users ENGINE=MyISAM;');
    }
}
