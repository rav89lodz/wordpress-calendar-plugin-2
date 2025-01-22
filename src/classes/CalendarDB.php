<?php

namespace CalendarPlugin\src\classes;

class CalendarDB
{
    private static $tableName;

    /**
     * Constructor
     */
    public function __construct() {
        self::$tableName = 'calendar_plugin_data';
    }

    /**
     * Create table on DB
     * 
     * @return void
     */
    public static function create_db_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$tableName;
        $charset_collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$charset_collate = $wpdb->get_charset_collate();
		}

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            calendar_key_type varchar(255) NULL,
            calendar_value text NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            deleted_at datetime NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);
    }

    /**
     * Drop plugins table
     * 
     * @return void
     */
    public static function drop_db_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$tableName;

        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }

    /**
     * Get data by type
     * 
     * @param string type
     * @param boolean withTrashed
     * @return object
     */
    public static function get_data_by_type($type, $withTrashed = false) {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$tableName;

        $sql = "SELECT * FROM $table_name WHERE calendar_key_type = %s AND deleted_at IS NULL";

        if($withTrashed === true) {
            $sql = "SELECT * FROM $table_name WHERE calendar_key_type = %s";
        }

        return $wpdb->get_row($wpdb->prepare($sql, $type));
    }

    /**
     * Get data by type
     * 
     * @param string type
     * @param boolean withTrashed
     * @return array
     */
    public static function get_all_data_by_type($type, $withTrashed = false, $desc = false) {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$tableName;

        $sql = "SELECT * FROM $table_name WHERE calendar_key_type = %s AND deleted_at IS NULL";

        if($withTrashed === true) {
            $sql = "SELECT * FROM $table_name WHERE calendar_key_type = %s";
        }

        if($desc === true) {
            $sql .= " ORDER BY id DESC";
        }

        return $wpdb->get_results($wpdb->prepare($sql, $type));
    }

    /**
     * Get data by id
     * 
     * @param int id
     * @param boolean withTrashed
     * @return array|object|null
     */
    public static function get_data_by_id($id, $withTrashed = false) {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$tableName;

        $sql = "SELECT * FROM $table_name WHERE id = %d AND deleted_at IS NULL";

        if($withTrashed === true) {
            $sql = "SELECT * FROM $table_name WHERE id = %d";
        }

        return $wpdb->get_row($wpdb->prepare($sql, $id));
    }

    /**
     * Insert plugins data
     * 
     * @param string type
     * @param string data
     * @return int|bool
     */
    public static function insert_data($type, $data) {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$tableName;
    
        return $wpdb->insert($table_name, ['calendar_key_type' => $type, 'calendar_value' => $data]);
    }

    /**
     * Update plugins data
     * 
     * @param int id
     * @param string type
     * @param string data
     * @return int|bool
     */
    public static function update_data($id, $type, $data) {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$tableName;

        return $wpdb->update($table_name, ['calendar_key_type' => $type, 'calendar_value' => $data], ['id' => $id ]);
    }

    /**
     * Soft delete plugins data
     * 
     * @param int id
     * @return int|bool
     */
    public static function soft_delete_data($id) {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$tableName;

        return $wpdb->update($table_name, ['deleted_at' => current_time( 'mysql' )], ['id' => $id ]);
    }

    /**
     * Permanent delete plugins data
     * 
     * @param int id
     * @return int|bool
     */
    public static function delete_data($id) {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$tableName;

        $sql = "DELETE FROM $table_name WHERE id = %d";

        return $wpdb->query($wpdb->prepare($sql, $id));
    }
}