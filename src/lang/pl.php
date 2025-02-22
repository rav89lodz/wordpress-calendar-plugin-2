<?php

return [
    'addActivityFriendlyNames' => [
        'add_activity_user_name' => 'Imię i nazwisko',
        'add_activity_user_email' => 'Adres email',
        'add_activity_user_phone' => 'Numer telefonu',
        'add_activity_date' => 'Proponowana data zajęć',
        'add_activity_time_start' => 'Godzina rozpoczęcia zajęć',
        'add_activity_time_end' => 'Godzina zakończenia zajęć',
        'add_activity_name' => 'Nazwa zajęć',
    ],

    'reservationFriendlyNames' => [
        'user_name' => 'Imię i nazwisko',
        'user_email' => 'Adres email',
        'reservation_date' => 'Data rezerwacji zajęć',
        'reservation_time' => 'Godzina rezerwacji zajęć',
        'activity_name' => 'Nazwa zajęć',
        'reservation_id' => 'Unikalny numer zajęć',
    ],

    'searchBar' => [
        'search' => 'Szukaj...',
        'search_btn' => 'Szukaj',
        'search_bar_option_serach1' => 'Zawiera',
        'search_bar_option_serach2' => 'Zaczyna się od',
        'search_bar_option_serach3' => 'Równa się',
    ],

    'adminMenu' => [
        'save' => 'Zapisz',
        'form_send' => 'Wyślij',
        'form_cancel' => 'Anuluj',
        'save_success' => 'Poprawnie zapisano dane',
        'save_failed' => 'Nie udało się zapisać danych',
        'add_new' => 'Dodaj nowy',
        'title_label_places' => 'Nazwa miejsca',
        'title_label_grid' => 'Nazwa zajęć',
        'change_status_label' => 'Zmiana statusu',
        'change_status' => 'Zmień status',
        'edit_label' => 'Edycja',
        'delete_label' => 'Usuwanie',
        'edit' => 'Edytuj',
        'delete' => 'Usuń',
        'copy_clipboard' => 'Skopiowano!',
        'next' => 'Następna',
        'prev' => 'Poprzednia',
        'delete_question' => 'Czy na pewno chcesz usunąć ten element?',
        'activity_type_place' => 'Miejsce zajęć',
        'grid_activity_days' => 'Dzień / dni zajęć',
        'activity_is_active' => 'Widoczne w kalendarzu',
        'update_success' => 'Zaktualizowano',
        'update_failed' => 'Problem z aktualizacją danych',
        'status_accepted' => 'Zaakceptuj',
        'status_rejected' => 'Odrzuć',
        'grid_status_label' => 'Status',
    ],

    'excludedActivityFriendlyNames' => [
        'excluded_activity_date' => 'Data wykluczenia',
        'excluded_activity_time_start' => 'Godzina rozpoczęcia',
        'excluded_activity_time_end' => 'Godzina zakończenia',
        'excluded_activity_name' => 'Opis',
        'excluded_activity_is_active' => 'Widoczne w kalendarzu',
        'excluded_all_day_long' => 'Cały dzień',
    ],

    'excludedActivityMenu' => [
        'excluded_menu_description' => 'Wykluczenia dni w kalendarzu',
        'excluded_menu_name' => 'Wykluczenia dni',
        'excluded_menu_singular_name' => 'Wykluczenie dni',
        'excluded_menu_meta_box_title' => 'Wykluczenia dni w kalendarzu'
    ],

    'addActivityMenu' => [
        'activity_menu_description' => 'Rezerwacje terminów w kalendarzu',
        'activity_menu_name' => 'Rezerwacje',
        'activity_menu_singular_name' => 'Rezerwacja',
        'activity_menu_meta_box_title' => 'Rezerwacja zajęć'
    ],

    'reservationMenu' => [
        'reservation_menu_description' => 'Zapisy na zajęcia',
        'reservation_menu_name' => 'Zapisy na zajęcia',
        'reservation_menu_singular_name' => 'Zapis na zajęcia',
        'reservation_menu_meta_box_title' => 'Zapis na zajęcia'
    ],

    'addActivityMessage' => [
        'subject' => 'Prośba o dopisanie zajęć do kalendarza',
        'message_from' => 'Wiadomość od',
        'post_title' => 'Dopisanie zajęć do kalendarza: ',
        'message_beginning' => 'Prośba o dopisanie do kalendarza zajęć: ',
    ],

    'reservationMessage' => [
        'subject' => 'Zapis na zajęcia z kalendarza rezerwacji',
        'message_from' => 'Wiadomość od',
        'post_title' => 'Zapis na zajęcia: ',
        'message_beginning_success' => 'Zapisano na zajęcia',
        'message_beginning_failure' => 'Nie zapisano na zajęcia z powodu przekroczenia limitu miejsc na zajęciach',
    ],

    'calendarLabels' => [
        'label_activity_start_at' => 'Godzina rozpoczęcia',
        'label_activity_end_at' => 'Godzina zakończenia',
        'label_excluded_start_at' => 'Nieczynne od',
        'label_excluded_end_at' => 'Nieczynne do',
        'label_activity_from' => 'Zajęcia od',
        'label_excluded_from' => 'Nieczynne od',
        'label_excluded_all_day_long' => 'Nieczynne cały dzień',
        'label_activity_to' => 'do',
        'default_success_message' => 'Wiadmość została wysłana. Rezerwacja dokonana',
        'default_error_message' => 'Wiadmość została wysłana. Rezerwacja odrzucona',
        'email_error_message' => 'Problem z wysłaniem wiadomości email',
        'config_error' => 'Błędne daty / godziny w konfiguracji kalendarza',
    ],

    'modalFormFriendlyNames' => [
        'send' => 'Wyślij',
        'cancel' => 'Anuluj',
        'date_calendar_add_activity_text_muted' => 'W przypadku zajęć cyklicznych należy podać dni tygodnia',
        'add_activity_title' => 'Formularz zgłoszenia zajęć w kalendarzu',
        'reservation_title' => 'Zapis na zajęcia',
        'reservation_limit_over_title' => 'Zapis na zajęcia - limit wyczerpany',
        'reservation_limit_over_message' => 'Zapis na zajęcia jest niemożliwy. Limit miejsc został wyczerpany',
        'reservation_limit_over_confirm' => 'Ok',
        'add_activity_active_button' => 'Zgłoś zajęcia do kalendarza',
        'user_name_calendar_add_activity' => 'Imię i nazwisko',
        'user_email_calendar_add_activity' => 'Adres email',
        'user_phone_calendar_add_activity' => 'Numer telefonu',
        'date_calendar_add_activity' => 'Proponowana data zajęć',
        'time_start_calendar_add_activity' => 'Godzina rozpoczęcia zajęć',
        'time_end_calendar_add_activity' => 'Godzina zakończenia zajęć',
        'name_calendar_add_activity' => 'Nazwa zajęć',
        'reservation_day' => 'Zapis na zajęcia w dniu',
        'reservation_hour' => 'o godzinie',
    ],

    'days' => [
        'monday' => 'Poniedziałek',
        'tuesday' => 'Wtorek',
        'wednesday' => 'Środa',
        'thursday' => 'Czwartek',
        'friday' => 'Piątek',
        'saturday' => 'Sobota',
        'sunday' => 'Niedziela',
    ],

    'months' => [
        'january' => 'Styczeń',
        'february' => 'Luty',
        'march' => 'Marzec',
        'april' => 'Kwiecień',
        'may' => 'Maj',
        'june' => 'Czerwiec',
        'july' => 'Lipiec',
        'august' => 'Sierpień',
        'september' => 'Wrzesień',
        'october' => 'Październik',
        'november' => 'Listopad',
        'december' => 'Grudzień',
    ],

    'optionPage' => [
        'main_menu_settings' => 'Ustawienia Kalendarza Rezerwacji',
        'main_short_code' => 'Użyj tego kodu (short_code), żeby wyświetlić na stronie kalendarz z pełnymi danymi.',
        'main_short_code_form' => 'Użyj tego kodu (short_code), żeby wyświetlić formularz kontaktowy, dzięki któremu będziesz otrzymywać wiadomości, że ktoś chce zarezerwować termin w kalendarzu.',
        'main_menu_field1_name' => 'Umożliwić zapis na zajęcia poprzez kliknięcie na kalendarzu',
        'main_menu_field1_description' => 'Po kliknięciu na wybrane pole w kalendarzu pojawi się formularz zapisu na zajęcia. Formularz uwzględnia limit miejsc na zajęciach',
        'main_menu_field2_name' => 'Przenikanie kafelka z kolorem zajęć',
        'main_menu_field2_description' => 'Kafelek z kolorem będzie rozciągnięty w godzinach trwania zajęć. Ustaw, jeżeli zajęcia nie nakładają się na siebie',
        'main_menu_field3_name' => 'Wyświetlanie czasu zajęć',
        'main_menu_field3_description' => 'Włącz tę opcję, jeśli na kafelku zajęć ma pokazywać się czas zajęć w minutach',
        'main_menu_field4_name' => 'Wyświetlanie godziny zakończenia zajęć',
        'main_menu_field4_description' => 'Włącz tę opcję, jeśli na kafelku zajęć ma pokazywać się godzina zakończenia zajęć',
        'main_menu_field5_name' => 'Adres e-mail',
        'main_menu_field5_description' => 'Adres e-mail, na który będą wysłane powiadomienia. Jeśli chcesz ustawić kilka adresów, rozdziel je znakiem przecinka ","',
        'main_menu_field6_name' => 'Treść powiadomienia o przyjętym zapisie na zajęcia',
        'main_menu_field6_description' => 'Wpisz treść powiadomienia, którą chcesz, aby użytkownik otrzymał w przypdaku przyjętego zapisu na zajęcia. Możesz użyć {name} jako zmiennej',
        'main_menu_field7_name' => 'Treść powiadomienia o odrzuconym zapisie na zajęcia',
        'main_menu_field7_description' => 'Wpisz treść powiadomienia, którą chcesz, aby użytkownik otrzymał w przypdaku odrzucenia zapisu na zajęcia. Możesz użyć {name} jako zmiennej',
        'main_menu_field8_name' => 'Treść powiadomienia potwierdzająca wysyłkę formularza rezerwacji',
        'main_menu_field8_description' => 'Wpisz treść powiadomienia, którą chcesz, aby użytkownik otrzymał po wysłaniu formularza rezerwacji terminu w kalendarzu. Możesz użyć {name} jako zmiennej',
        'main_menu_field9_name' => 'Początkowa godzina w kalendarzu',
        'main_menu_field10_name' => 'Końcowa godzina w kalendarzu',
        'main_menu_field11_name' => 'Interwał kalendarza',
        'main_menu_field12_name' => 'Wyświetlanie miejsca zajęć',
        'main_menu_field12_description' => 'Włącz tę opcję, jeśli na kafelku ma pokazywać się miejsce odbywania się zajęć',
        'main_menu_field13_name' => 'Wyświetlanie kalendarza w poziomie',
        'main_menu_field13_description' => 'Włącz tę opcję, jeśli kalendarz ma się wyświetlać w poziomie. Daty i dni tygodnia będą na boku, a godziny na górze siatki kalendarza',
        'main_menu_field14_name' => 'Dostęp do ustawień dla wszystkich użytkowników',
        'main_menu_field14_description' => 'Włącz tę opcję, jeśli chcesz dać możliwość zarządzania kalendarzem wszystkim użytkownikom. Domyślnie, uprawnienia te ma tylko administrator',
        'main_menu_field15_name' => 'Włącz dodatkowy pasek przewijania na kalendarzu',
        'main_menu_field15_description' => 'Włącz tę opcję, jeśli chcesz włączyć pasek przewijania (scroll bar) w obszarze kalendarza. Kalendarz będzie miał ograniczoną wysokość / szerokość.',
        'main_menu_field16_name' => 'Ustaw szerokość siatki kalendarza w px',
        'main_menu_field16_description' => 'Podaj wartość liczbową dla szerokości siatki kalendarza w px, zgodnie ze standardami CSS, np. 1200px. Zostaw puste jeśli chcesz korzystać z wartości domyślnej: 1200px',
        'main_menu_field17_name' => 'Ustaw wysokość siatki kalendarza w px',
        'main_menu_field17_description' => 'Podaj wartość liczbową dla wysokości siatki kalendarza w px, zgodnie ze standardami CSS, np. 800px. Zostaw puste jeśli chcesz korzystać z wartości domyślnej: 100%',
        'main_menu_field18_name' => 'Ustaw wysokość komórki kalendarza w px',
        'main_menu_field18_description' => 'Podaj wartość liczbową dla wysokości komórki kalendarza w px, zgodnie ze standardami CSS, np. 120px. Zostaw puste jeśli chcesz korzystać z wartości domyślnej: 80px',
        'main_menu_field19_name' => 'Kalendarz w widoku pojedynczego dnia',
        'main_menu_field19_description' => 'Włącz, jeśli chcesz pokazywać kalendarz w widoku pojedynczego dnia. Domyślna wartość to widok w ujęciu tygodnia',
        'main_menu_field20_name' => 'Wyświetlanie godziny rozpoczęcia zajęć',
        'main_menu_field20_description' => 'Włącz tę opcję, jeśli na kafelku zajęć ma pokazywać się godzina rozpoczęcia zajęć',
        'main_menu_field21_name' => 'Dodaj klucz witryny recaptcha',
        'main_menu_field21_description' => 'Jeśli chcesz używać recaptcha v3 dodaj klucz witryny',
        'main_menu_field22_name' => 'Dodaj tajny klucz recaptcha',
        'main_menu_field22_description' => 'Jeśli chcesz używać recaptcha v3 dodaj tajny klucz',
        'main_menu_field23_name' => 'W jaki sposób obliczać limit miejsc na zajęcia',
        'main_menu_field24_name' => 'Usunąć kafelek po osiągnięciu limitu',
        'main_menu_field24_description' => 'Zaznacz, jeśli chcesz, aby kafelek w kalendarzu został usunięty po osiągnięciu limitu zapisów na zajęcia',
        'main_menu_field25_name' => 'Ustaw kolor kafelka po osiągnięciu limitu',
        'main_menu_field25_description' => 'Zaznacz, jeśli chcesz, ustawić kolor dla kafelka po osiągnięciu limitu zapisów na zajęcia',
        'main_menu_field26_name' => 'Wybierz kolor kafelka po osiągnięciu limitu',
        'main_menu_field26_description' => 'Wybierz kolor, którym chcesz wyróżnić kafelek w kalendarzu, po osiągnięciu limitu zapisów na zajęcia',
        'main_menu_field27_name' => 'Usuwanie ostatniej godziny',
        'main_menu_field27_description' => 'Zaznacz, jeśli chcesz, żeby ostatnia godzina w kalendarzu nie była wyświetlana',
        'main_menu_textarea_field_placeholder' => 'Wpisz treść wiadomości',
        'short_code_activity_place' => 'Użyj tego kodu [short_code], żeby wyświetlić na stronie kalendarz z danymi dla wybranego miejsca',
        'activity_place_title' => 'Miejsca zajęć',
        'activity_place_description' => 'Zdefiniuj miejsca, w których odbywają się zajęcia',
        'activity_place_field1_name' => 'Nazwa miejsca',
        'activity_place_field1_description' => 'Zdefiniuj nazwy miejesc, gdzie odbywają się zajęcia. Np. hala sportowa, sala nr 1, pływalnia',
        'calendar_grid_data_title' => 'Zajęcia w kalendarzu',
        'calendar_grid_data_description' => 'Zdefiniuj kalendarz rezerwacji',
        'calendar_grid_data_field1_name' => 'Nazwa zajęć',
        'calendar_grid_data_field2_name' => 'Godzina rozpoczęcia zajęć',
        'calendar_grid_data_field3_name' => 'Godzina zakończenia zajęć',
        'calendar_grid_data_field4_name' => 'Czy zajęcia cykliczna',
        'calendar_grid_data_field5_name' => 'Data zajęcia',
        'calendar_grid_data_field5_description' => 'Ustaw datę dla zajęcia występującego jednorazowo',
        'calendar_grid_data_field6_name' => 'Wybierz dzień zajęć cyklicznych',
        'calendar_grid_data_field7_name' => 'Kolor kafelka zajęć',
        'calendar_grid_data_field7_description' => 'Wybierz kolor, którym chcesz wyróżnić zajęcia w kalendarzu',
        'calendar_grid_data_field8_name' => 'Wybierz miejsce zajęć',
        'calendar_grid_data_field8_description' => 'Wybierz, w którym miejscu (ze zdefiniowanych) odbywają się zajęcia',
        'calendar_grid_data_field9_name' => 'Ile miejsc na zajęcia',
        'calendar_grid_data_field9_description' => 'Zdefiniuj ile razy można zarezerować zajęcia. Minimalna wartość to 1',
        'calendar_grid_data_field10_name' => 'Status rezerwacji',
        'calendar_grid_data_field11_name' => 'Data rozpoczęcia zajęć cyklicznych',
        'calendar_grid_data_field11_description' => 'Ustaw pierwszy dzień dla zajęć cyklicznych. Jeśli chcesz, żeby zajęcia trwały bez ograniczeń czasowych, zostaw to pole puste',
        'calendar_grid_data_field12_name' => 'Data zakończenia zajęć cyklicznych',
        'calendar_grid_data_field12_description' => 'Ustaw ostatni dzień dla zajęć cyklicznych. Jeśli chcesz, żeby zajęcia trwały bez ograniczeń czasowych, zostaw to pole puste',
        'calendar_grid_data_field13_name' => 'Data wykluczenia zajęć cyklicznych',
        'calendar_grid_data_field13_description' => 'Ustaw datę w formacie rrrr-mm-dd dla wykluczenia wydarzenia. Jeśli chcesz ustawić kilka dat, rozdziel je znakiem średnika ";"
                    np. ' . date('Y') . '-01-31 ;'. date('Y') . '-05-31. Jeśli chcesz, aby wykluczenie było co roku, ustaw datę w formacie %-mm-dd np. %-01-31',
        'calendar_grid_data_field14_name' => 'Opis widoczny w kalendarzu',
        'calendar_grid_data_field14_description' => 'Napisz, dlaczego ten dzień jest niedostępny, np. urlop, remont',
        'calendar_grid_data_field15_name' => 'Godzina rozpoczęcia',
        'calendar_grid_data_field15_description' => 'Zostaw to pole puste, jeśli chcesz wykluczyć cały dzień',
        'calendar_grid_data_field16_name' => 'Godzina zakończenia',
        'calendar_grid_data_field17_name' => 'Kolor kafelka',
        'calendar_grid_data_field17_description' => 'Wybierz kolor, którym chcesz wyróżnić niedostępny dzień w kalendarzu',
        'calendar_grid_data_field18_name' => 'Data wykluczenia',
        'calendar_grid_data_field19_name' => 'Wybierz typ zajęć z kalendarza',
        'calendar_grid_data_field19_description' => 'Wybierz typ zajęć, które chcesz oznaczyć jako dzień niedostępny',
        'limit_option1' => 'Po zgłoszeniach wysłanych',
        'limit_option2' => 'Po zgłoszeniach zaakceptowanych',
    ],
];