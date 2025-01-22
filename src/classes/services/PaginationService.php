<?php

namespace CalendarPlugin\src\classes\services;

class PaginationService
{
    private static $pageLinks;

    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Get page links
     * 
     * @return string|string[]|void
     */
    public static function get_links() {
        return self::$pageLinks;
    }

    /**
     * Return paginated items
     * 
     * @param array|null items
     * @return array
     */
    public static function paginate($items) {
        $totalItems = count($items);
        $totalPages = ceil($totalItems / CALENDAR_PLUGIN_PER_PAGE);

        $currentPage  = isset($_GET['paged']) ? (int) $_GET['paged'] : 1;
        $startIndex = ($currentPage - 1) * CALENDAR_PLUGIN_PER_PAGE;

        $currentPageItems = array_slice($items, $startIndex, CALENDAR_PLUGIN_PER_PAGE);

        self::set_page_links($currentPage, $totalPages);

        return $currentPageItems;
    }

    /**
     * Set page links
     * 
     * @param int currentPage
     * @param int totalPages
     * @return void
     */
    private static function set_page_links($currentPage, $totalPages) {
        $service = new LanguageService('adminMenu');
        self::$pageLinks = '<div class="d-flex justify-content-center my-padding-class">' . paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '&paged=%#%',
            'current' => $currentPage,
            'total' => $totalPages,
            'before_page_number' => ' ',
            'prev_text'    => '<span style="margin-right: 10px">« ' . $service->langData['prev'] . '</span>',
            'next_text'    => '<span style="margin-left: 10px">' . $service->langData['next'] . ' »</span>',
        )) . '</div>';
    }
}