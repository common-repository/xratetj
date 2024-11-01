<?php
/*
Plugin Name: XRateTJ
Plugin URI: http://xrate.code.tj/
Description: Tajik somoni exchange rates plugin.
Version: 1.0.1
Author: CODE.TJ
Author URI: http://code.tj/
License: GPLv2 or later
Text Domain: xratetj
*/

/*  2016  RK, CODE.TJ team

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class xratetj extends WP_Widget {

    // constructor
    function xratetj() {
        parent::WP_Widget(false, $name = __('XRateTJ', 'xratetj') );
    }

    // widget form creation
    function form($instance) { 
    // Check values
        if( $instance) {
            $xratetj_title = esc_attr($instance['xratetj_title']);
            $xratetj_bank = esc_attr($instance['xratetj_bank']);
        } else {
            $xratetj_title = '';
            $xratetj_bank = '';
        }
        ?>

        <p>
        <label for="<?php echo $this->get_field_id('xratetj_title'); ?>"><?php _e('Widget Title', 'xratetj'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('xratetj_title'); ?>" name="<?php echo $this->get_field_name('xratetj_title'); ?>" type="text" value="<?php echo $xratetj_title; ?>" />
        </p>

        <p>
        <label for="<?php echo $this->get_field_id('xratetj_bank'); ?>"><?php _e('Select', 'xratetj'); ?></label>
        <select name="<?php echo $this->get_field_name('xratetj_bank'); ?>" id="<?php echo $this->get_field_id('xratetj_bank'); ?>" class="widefat">
        <?php
        $options = array('nbt', 'agro');
        foreach ($options as $option) {
        echo '<option value="' . $option . '" id="' . $option . '"', $xratetj_bank == $option ? ' selected="selected"' : '', '>', $option, '</option>';
        }
        ?>
        </select>
        </p>

    <?php
    }

    // widget update
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        // Fields
        $instance['xratetj_title'] = strip_tags($new_instance['xratetj_title']);
        $instance['xratetj_bank'] = strip_tags($new_instance['xratetj_bank']);
        return $instance;
    }

    // widget display
     function widget($args, $instance) {
        extract( $args );
        // these are the widget options
        $xratetj_title = apply_filters('widget_title', $instance['xratetj_title']);
        $xratetj_bank = $instance['xratetj_bank'];
        echo $before_widget;
        // Display the widget
        echo '<div class="widget-text xratetj_box">';
        // Check if title is set
        if ( $xratetj_title ) {
          echo $before_title . $xratetj_title . $after_title;
        }

        if ( $xratetj_bank ) {
            $xratetj_st='';
            if($xratetj_bank=='nbt') $xratetj_st='Национальный Банк Таджикистана';
            if($xratetj_bank=='agro') $xratetj_st='Агроинвестбанк';
            if($xratetj_bank=='tsb') $xratetj_st='Тоджиксодиротбанк';
            $xratetj_url = 'http://xrate.code.tj/?bank='.$xratetj_bank;
            $xratetj_json = file_get_contents($xratetj_url);
            $xratetj_rate = json_decode($xratetj_json, true);
            //$xratetj_path = plugin_dir_path(__FILE__).'img';
			switch ($xratetj_bank) {
				case 'nbt':
					echo '<style>
					.xrate_box {
					    width:200px;
					    border:1px solid #EFEFEF;
					    border-radius:8px;
					    padding:10px 0px;
					    color:#444444;
					}
					.xrate_title {
					    font-weight:bold;
					    text-align:center;
					    margin-bottom:10px;
					}
					.xrate_date {
					    font-weight:bold;
					    text-align:center;
					    margin-bottom:10px;
					    color:blue;
					    font-size:13px;
					}
					.xrate_line{
					    text-align:center;
					    margin-bottom:5px;
					}
					.xrate_el {
					    display:inline;
					    width:23%;
					    padding:2px;
					}
					.xrate_flag {
					    width:28px;
					    height:15px;
					    display:inline-block;
					    background-repeat:no-repeat;
					}
					.xr_flag_usd {
					    background-image:url(./wp-content/plugins/xratetj/img/flag_usd.gif);
					}
					.xr_flag_eur {
					    background-image:url(./wp-content/plugins/xratetj/img/flag_eur.gif);
					}
					.xr_flag_rub {
					    background-image:url(./wp-content/plugins/xratetj/img/flag_rub.gif);
					}
					</style>
					<div id="'.$xratetj_bank.'_xrate" class="xrate_box">

					<div class="xrate_title">'.$xratetj_st.'</div>

					<div class="xrate_date">Курс на '.$xratetj_rate[$xratetj_bank]['buy']['date'].'</div>
					    <div class="xrate_line">
					        <div class="xrate_el xrate_flag xr_flag_usd"></div>
					        <div class="xrate_el xrate_cur1">1 USD =</div>
					        <div class="xrate_el xrate_rate">'.$xratetj_rate[$xratetj_bank]['buy']['USD'].'</div>
					        <div class="xrate_el xrate_cur"> TJS</div>
					    </div>
					    <div class="xrate_line">
					        <div class="xrate_el xrate_flag xr_flag_eur"></div>
					        <div class="xrate_el xrate_cur1">1 EUR =</div>
					        <div class="xrate_el xrate_rate">'.$xratetj_rate[$xratetj_bank]['buy']['EUR'].'</div>
					        <div class="xrate_el xrate_cur"> TJS</div>
					    </div>
					    <div class="xrate_line">
					        <div class="xrate_el xrate_flag xr_flag_rub"></div>
					        <div class="xrate_el xrate_cur1">1 RUB =</div>
					        <div class="xrate_el xrate_rate">'.$xratetj_rate[$xratetj_bank]['buy']['RUB'].'</div>
					        <div class="xrate_el xrate_cur"> TJS</div>
					    </div>
					</div>';
					break;
					case 'agro':
					echo '<style>
					.xrate_box {
					    width:200px;
					    border:1px solid #EFEFEF;
					    border-radius:8px;
					    padding:10px 0px;
					    color:#444444;
					}
					.xrate_title {
					    font-weight:bold;
					    text-align:center;
					    margin-bottom:10px;
					}
					.xrate_date {
					    font-weight:bold;
					    text-align:center;
					    margin-bottom:10px;
					    color:blue;
					    font-size:13px;
					}
					.xrate_line{
					    text-align:center;
					    margin-bottom:5px;
					}
					.xrate_buy_sale {
						font-size:12px;
						color:green;
					}
					.xrate_el {
					    display:inline;
					    width:23%;
					    padding:2px;
					}
					.xrate_flag {
					    width:14px;
					    height:13px;
					    display:inline-block;
					    background-repeat:no-repeat;
					}
					.xr_flag_usd {
					    background-image:url(./wp-content/plugins/xratetj/img/agro/flag_us.png);
					}
					.xr_flag_eur {
					    background-image:url(./wp-content/plugins/xratetj/img/agro/flag_eu.png);
					}
					.xr_flag_rub {
					    background-image:url(./wp-content/plugins/xratetj/img/agro/flag_ru.png);
					}
					</style>
					<div id="'.$xratetj_bank.'_xrate" class="xrate_box">

					<div class="xrate_title">'.$xratetj_st.'</div>

					<div class="xrate_date">Курс валют на '.$xratetj_rate[$xratetj_bank]['buy']['date'].'</div>
					    <div class="xrate_line">
					        <div class="xrate_el"></div>
					        <div class="xrate_el xrate_buy_sale">Покупка</div>
					        <div class="xrate_el xrate_buy_sale">Продажа</div>
					        <div class="xrate_el"></div>
					    </div>
					    <div class="xrate_line">
					        <div class="xrate_el xrate_flag xr_flag_usd"></div>
					        <div class="xrate_el xrate_cur1">'.$xratetj_rate[$xratetj_bank]['buy']['USD'].'</div>
					        <div class="xrate_el xrate_rate">'.$xratetj_rate[$xratetj_bank]['sale']['USD'].'</div>
					        <div class="xrate_el xrate_cur"> TJS</div>
					    </div>
					    <div class="xrate_line">
					        <div class="xrate_el xrate_flag xr_flag_eur"></div>
					        <div class="xrate_el xrate_cur1">'.$xratetj_rate[$xratetj_bank]['buy']['EUR'].'</div>
					        <div class="xrate_el xrate_rate">'.$xratetj_rate[$xratetj_bank]['sale']['EUR'].'</div>
					        <div class="xrate_el xrate_cur"> TJS</div>
					    </div>
					    <div class="xrate_line">
					        <div class="xrate_el xrate_flag xr_flag_rub"></div>
					        <div class="xrate_el xrate_cur1">'.$xratetj_rate[$xratetj_bank]['buy']['RUB'].'</div>
					        <div class="xrate_el xrate_rate">'.$xratetj_rate[$xratetj_bank]['sale']['RUB'].'</div>
					        <div class="xrate_el xrate_cur"> TJS</div>
					    </div>
					</div>';
					break;
			}

        }

        echo '</div>';
        echo $after_widget;
    }

}



// register widget
add_action('widgets_init', create_function('', 'return register_widget("xratetj");'));