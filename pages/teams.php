<?php
/**
 * Teams Management UI
 */

$page_security = 'SA_TEAMS';
$path_to_root = "../../..";
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/modules/FA_Teams/includes/teams_db.inc");

page(_("Teams"), false, false, "", "");

$teams = get_teams();
start_table(TABLESTYLE);
table_header([_('Name'), _('Manager'), _('Members'), _('Action')]);
while ($t = db_fetch($teams)) {
    alt_table_row($t);
    label_cell("<b>" . $t['name'] . "</b>");
    label_cell($t['manager_name'] ?? '-');
    label_cell($t['member_count']);
    echo "<td><a href='?view=" . $t['id'] . "'>" . _("View") . "</a></td>";
}
end_table(1);
end_page(true);