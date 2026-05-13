# Teams UI - Architecture

## Document Information

| Field | Value |
|-------|-------|
| **Module Name** | ksf_Teams_UI |
| **Module Type** | UI Adapter |
| **Platform** | FrontAccounting |
| **Version** | 1.0.0 |

---

## 1. Technical Architecture

### 1.1 Architecture Pattern

```
┌──────────────────────────────────────────────────────────────────────┐
│                      ksf_Teams_UI                                    │
│                 (FrontAccounting UI Adapter)                         │
├──────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐     │
│  │                 teams.php (FA Page)                          │     │
│  │  Responsibilities:                                          │     │
│  │  - Fetch team data from database                            │     │
│  │  - Render FA table with team information                    │     │
│  │  - Provide action links to team views                       │     │
│  └──────────────────────────────────────────────────────────────┘     │
│                                                                      │
├──────────────────────────────────────────────────────────────────────┤
│                         FrontAccounting Platform                      │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐           │
│  │   UI.inc       │  │  session.inc  │  │  teams_db.inc │           │
│  │   page(),     │  │  page_security│  │  get_teams() │           │
│  │   start_table │  │  SA_TEAMS     │  │             │           │
│  └────────────────┘  └────────────────┘  └────────────────┘           │
└──────────────────────────────────────────────────────────────────────┘
```

### 1.2 Directory Structure

```
ksf_Teams_UI/
├── includes/
│   └── teams_ui_functions.php    # Helper functions
├── pages/
│   └── teams.php                # FA page entry point
├── tests/
├── ProjectDcs/
└── composer.json
```

### 1.3 Page Flow

```
┌──────────────┐    ┌──────────────────┐    ┌──────────────────┐
│   Browser    │    │   teams.php      │    │   teams_db.inc   │
│             │    │                  │    │                  │
└──────┬───────┘    └────────┬─────────┘    └───────┬──────────┘
       │                     │                        │
       │  GET /teams.php     │                        │
       │───────────────────▶│                        │
       │                    │                        │
       │                    │  get_teams()           │
       │                    │────────────────────────▶│
       │                    │                        │
       │                    │  [teams array]         │
       │                    │◀────────────────────────│
       │                    │                        │
       │                    │  Render FA Table      │
       │                    │                        │
       │  [HTML Page]        │                        │
       │◀───────────────────│                        │
       │                    │                        │
```

---

## 2. Data Flow

### 2.1 Team Data Input

```php
// From get_teams() function
$teams = [
    [
        'id' => 1,
        'name' => 'Sales Team',
        'manager_name' => 'John Smith',
        'member_count' => 5
    ],
    [
        'id' => 2,
        'name' => 'Support Team',
        'manager_name' => null,
        'member_count' => 3
    ]
];
```

### 2.2 Output Structure

```php
start_table(TABLESTYLE);
table_header(['Name', 'Manager', 'Members', 'Action']);

while ($t = db_fetch($teams)) {
    alt_table_row($t);  // Alternating row styling
    label_cell("<b>" . $t['name'] . "</b>");
    label_cell($t['manager_name'] ?? '-');
    label_cell($t['member_count']);
    echo "<td><a href='?view=" . $t['id'] . "'>View</a></td>";
}
end_table(1);
```

---

## 3. FA Table Styling

### 3.1 Table Functions Used

| Function | Purpose |
|----------|---------|
| start_table() | Begin table with FA styling |
| table_header() | Render header row |
| alt_table_row() | Alternating row colors |
| label_cell() | Render table cell with label |
| end_table() | Close table |

### 3.2 Table Structure

```html
<table class="tablestyle">
    <thead>
        <tr class="tablestyle_head">
            <th>Name</th>
            <th>Manager</th>
            <th>Members</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr class="tablestyle_row0">
            <td><b>Sales Team</b></td>
            <td>John Smith</td>
            <td>5</td>
            <td><a href="?view=1">View</a></td>
        </tr>
        <tr class="tablestyle_row1">
            <td><b>Support Team</b></td>
            <td>-</td>
            <td>3</td>
            <td><a href="?view=2">View</a></td>
        </tr>
    </tbody>
</table>
```

---

## 4. Integration Points

### 4.1 FrontAccounting Pages

| Page | File | Purpose |
|------|------|---------|
| Team List | pages/teams.php | Main teams listing |

### 4.2 Database Functions

| Function | Source | Purpose |
|----------|--------|---------|
| get_teams() | modules/FA_Teams/includes/teams_db.inc | Fetch team data |
| db_fetch() | FA database layer | Iterate results |

---

*Document Version: 1.0.0*  
*Author: KSFII Development Team*