create table branches
(
    id          int unsigned auto_increment
        primary key,
    name        varchar(255)                        not null,
    location    text                                not null,
    description text                                not null,
    status      varchar(255)                        not null,
    user_id     int                                 null,
    created_at  timestamp default CURRENT_TIMESTAMP not null,
    updated_at  timestamp default CURRENT_TIMESTAMP not null,
    deleted_at  timestamp                           null
)
    collate = utf8_unicode_ci;

create table migrations
(
    migration varchar(255) not null,
    batch     int          not null
)
    collate = utf8_unicode_ci;

create table tt_charges
(
    id          int unsigned auto_increment
        primary key,
    tt_amount   double(8, 2)                            not null,
    dollar_rate double(8, 2)                            not null,
    import_id   int                                     not null,
    created_at  timestamp default CURRENT_TIMESTAMP not null,
    updated_at  timestamp default CURRENT_TIMESTAMP not null,
    deleted_at  timestamp                               null
)
    collate = utf8_unicode_ci;

create table users
(
    id         int unsigned auto_increment
        primary key,
    name       varchar(255)                        not null,
    username   varchar(255)                        not null,
    email      varchar(255)                        not null,
    password   varchar(255)                        not null,
    phone      varchar(255)                        not null,
    role       varchar(255)                        not null,
    address    text                                not null,
    sex        varchar(255)                        not null,
    status     varchar(255)                        not null,
    deleted_at timestamp                           null,
    created_at timestamp default CURRENT_TIMESTAMP not null,
    updated_at timestamp default CURRENT_TIMESTAMP null,
    branch_id  int unsigned                        not null,
    constraint users_email_unique
        unique (email),
    constraint users_username_unique
        unique (username),
    constraint users_branch_id_foreign
        foreign key (branch_id) references branches (id)
)
    collate = utf8_unicode_ci;

create table account_categories
(
    id         int unsigned auto_increment
        primary key,
    name       varchar(255)                            not null,
    user_id    int unsigned                            not null,
    deleted_at timestamp                               null,
    created_at timestamp default CURRENT_TIMESTAMP not null,
    updated_at timestamp default CURRENT_TIMESTAMP not null,
    constraint account_categories_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table expenses
(
    id         int unsigned auto_increment
        primary key,
    invoice_id varchar(255)                            not null,
    category   varchar(255)                            not null,
    particular text                                    not null,
    purpose    text                                    not null,
    amount     double(8, 2)                            not null,
    remarks    text                                    not null,
    status     varchar(255)                            not null,
    user_id    int unsigned                            not null,
    deleted_at timestamp                               null,
    created_at timestamp default CURRENT_TIMESTAMP not null,
    updated_at timestamp default CURRENT_TIMESTAMP not null,
    branch_id  int unsigned                            not null,
    constraint expenses_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint expenses_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table imports
(
    id               int unsigned auto_increment
        primary key,
    import_num       varchar(255)                            not null,
    consignment_name varchar(255)                            not null,
    description      text                                    not null,
    branch_id        int unsigned                            not null,
    user_id          int unsigned                            not null,
    status           varchar(255)                            not null,
    created_at       timestamp default CURRENT_TIMESTAMP not null,
    updated_at       timestamp default CURRENT_TIMESTAMP not null,
    deleted_at       timestamp                               null,
    constraint imports_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint imports_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table bank_costs
(
    id                   int unsigned auto_increment
        primary key,
    lc_no                varchar(255)                            not null,
    bank_name            varchar(255)                            not null,
    lc_commission_charge double                                  not null,
    vat_commission       double                                  not null,
    stamp_charge         double                                  not null,
    swift_charge         double                                  not null,
    lca_charge           double                                  not null,
    insurance_charge     double                                  not null,
    bank_service_charge  double                                  not null,
    others_charge        double                                  not null,
    total_bank_cost      double                                  not null,
    import_id            int unsigned                            not null,
    deleted_at           timestamp                               null,
    created_at           timestamp default CURRENT_TIMESTAMP not null,
    updated_at           timestamp default CURRENT_TIMESTAMP not null,
    lc_date              varchar(255)                            not null,
    constraint bank_costs_import_id_foreign
        foreign key (import_id) references imports (id)
)
    collate = utf8_unicode_ci;

create table cnf_costs
(
    id                  int unsigned auto_increment
        primary key,
    clearing_agent_name varchar(255)                            not null,
    bill_no             varchar(255)                            not null,
    bank_no             varchar(255)                            not null,
    association_fee     double                                  not null,
    po_cash             double                                  not null,
    port_charge         double                                  not null,
    shipping_charge     double                                  not null,
    noc_charge          double                                  not null,
    labour_charge       double                                  not null,
    jetty_charge        double                                  not null,
    agency_commission   double                                  not null,
    others_charge       double                                  not null,
    total_cnf_cost      double                                  not null,
    import_id           int unsigned                            not null,
    deleted_at          timestamp                               null,
    created_at          timestamp default CURRENT_TIMESTAMP not null,
    updated_at          timestamp default CURRENT_TIMESTAMP not null,
    clearing_date       varchar(255)                            not null,
    constraint cnf_costs_import_id_foreign
        foreign key (import_id) references imports (id)
)
    collate = utf8_unicode_ci;

create table name_of_accounts
(
    id                  int unsigned auto_increment
        primary key,
    name                varchar(255)                            not null,
    account_category_id int unsigned                            not null,
    user_id             int unsigned                            not null,
    created_at          timestamp default CURRENT_TIMESTAMP not null,
    updated_at          timestamp default CURRENT_TIMESTAMP not null,
    opening_balance     double(8, 2)                            not null,
    branch_id           int unsigned                            not null,
    constraint name_of_accounts_account_category_id_foreign
        foreign key (account_category_id) references account_categories (id),
    constraint name_of_accounts_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint name_of_accounts_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table balance_transfers
(
    id                       int unsigned auto_increment
        primary key,
    from_branch_id           int unsigned                            not null,
    to_branch_id             int unsigned                            not null,
    from_account_category_id int unsigned                            not null,
    to_account_category_id   int unsigned                            not null,
    from_account_name_id     int unsigned                            not null,
    to_account_name_id       int unsigned                            not null,
    amount                   double(8, 2)                            not null,
    remarks                  text                                    not null,
    user_id                  int unsigned                            not null,
    created_at               timestamp default CURRENT_TIMESTAMP not null,
    updated_at               timestamp default CURRENT_TIMESTAMP not null,
    constraint balance_transfers_from_account_category_id_foreign
        foreign key (from_account_category_id) references account_categories (id),
    constraint balance_transfers_from_account_name_id_foreign
        foreign key (from_account_name_id) references name_of_accounts (id),
    constraint balance_transfers_from_branch_id_foreign
        foreign key (from_branch_id) references branches (id),
    constraint balance_transfers_to_account_category_id_foreign
        foreign key (to_account_category_id) references account_categories (id),
    constraint balance_transfers_to_account_name_id_foreign
        foreign key (to_account_name_id) references name_of_accounts (id),
    constraint balance_transfers_to_branch_id_foreign
        foreign key (to_branch_id) references branches (id),
    constraint balance_transfers_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table other_costs
(
    id                int unsigned auto_increment
        primary key,
    dollar_to_bd_rate double                                  not null,
    tt_charge         double                                  not null,
    import_id         int unsigned                            not null,
    deleted_at        timestamp                               null,
    created_at        timestamp default CURRENT_TIMESTAMP not null,
    updated_at        timestamp default CURRENT_TIMESTAMP not null,
    constraint other_costs_import_id_foreign
        foreign key (import_id) references imports (id)
)
    collate = utf8_unicode_ci;

create table parties
(
    id                  int unsigned auto_increment
        primary key,
    name                varchar(255)                            not null,
    contact_person_name varchar(255)                            not null,
    type                varchar(255)                            not null,
    phone               varchar(255)                            not null,
    email               varchar(255)                            not null,
    status              varchar(255)                            not null,
    address             text                                    not null,
    user_id             int unsigned                            not null,
    deleted_at          timestamp                               null,
    created_at          timestamp default CURRENT_TIMESTAMP not null,
    updated_at          timestamp default CURRENT_TIMESTAMP not null,
    balance             double                                  not null,
    constraint parties_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table product_categories
(
    id         int unsigned auto_increment
        primary key,
    name       varchar(255)                            not null,
    branch_id  int unsigned                            not null,
    user_id    int unsigned                            not null,
    created_at timestamp default CURRENT_TIMESTAMP not null,
    updated_at timestamp default CURRENT_TIMESTAMP not null,
    deleted_at timestamp                               null,
    constraint product_categories_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint product_categories_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table product_sub_categories
(
    id          int unsigned auto_increment
        primary key,
    name        varchar(255)                            not null,
    branch_id   int unsigned                            not null,
    category_id int unsigned                            not null,
    user_id     int unsigned                            not null,
    created_at  timestamp default CURRENT_TIMESTAMP not null,
    updated_at  timestamp default CURRENT_TIMESTAMP not null,
    deleted_at  timestamp                               null,
    constraint product_sub_categories_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint product_sub_categories_category_id_foreign
        foreign key (category_id) references product_categories (id),
    constraint product_sub_categories_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table products
(
    id              int unsigned auto_increment
        primary key,
    name            varchar(255) not null,
    origin          varchar(255) not null,
    hs_code         varchar(255) not null,
    total_quantity  double       not null,
    branch_id       int unsigned not null,
    category_id     int unsigned not null,
    sub_category_id int unsigned null,
    user_id         int unsigned not null,
    deleted_at      timestamp    null,
    product_type    varchar(255) not null,
    price           double       not null,
    min_level       varchar(255) not null,
    created_at      timestamp    null,
    updated_at      timestamp    null,
    constraint products_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint products_category_id_foreign
        foreign key (category_id) references product_categories (id),
    constraint products_sub_category_id_foreign
        foreign key (sub_category_id) references product_sub_categories (id),
    constraint products_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table import_details
(
    id                  int unsigned auto_increment
        primary key,
    quantity            double                                  not null,
    total_booking_price double                                  not null,
    total_cfr_price     double                                  not null,
    import_num          int unsigned                            not null,
    product_id          int unsigned                            not null,
    user_id             int unsigned                            not null,
    created_at          timestamp default CURRENT_TIMESTAMP not null,
    updated_at          timestamp default CURRENT_TIMESTAMP not null,
    deleted_at          timestamp                               null,
    stock_in_status     int                                     not null,
    constraint import_details_import_num_foreign
        foreign key (import_num) references imports (id),
    constraint import_details_product_id_foreign
        foreign key (product_id) references products (id),
    constraint import_details_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table proforma_invoices
(
    id               int unsigned auto_increment
        primary key,
    invoice_no       varchar(255)                            not null,
    beneficiary_name varchar(255)                            not null,
    terms            varchar(255)                            not null,
    import_id        int unsigned                            not null,
    deleted_at       timestamp                               null,
    created_at       timestamp default CURRENT_TIMESTAMP not null,
    updated_at       timestamp default CURRENT_TIMESTAMP not null,
    constraint proforma_invoices_import_id_foreign
        foreign key (import_id) references imports (id)
)
    collate = utf8_unicode_ci;

create table purchase_invoices
(
    id         int unsigned auto_increment
        primary key,
    invoice_id varchar(255)                            not null,
    party_id   int unsigned                            not null,
    status     varchar(255)                            not null,
    user_id    int unsigned                            not null,
    created_at timestamp default CURRENT_TIMESTAMP not null,
    updated_at timestamp default CURRENT_TIMESTAMP not null,
    deleted_at timestamp                               null,
    constraint purchase_invoices_party_id_foreign
        foreign key (party_id) references parties (id),
    constraint purchase_invoices_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table sales
(
    id                      int unsigned auto_increment
        primary key,
    invoice_id              varchar(255)                            not null,
    party_id                int unsigned                            null,
    status                  varchar(255)                            not null,
    user_id                 int unsigned                            not null,
    created_at              timestamp default CURRENT_TIMESTAMP not null,
    updated_at              timestamp default CURRENT_TIMESTAMP not null,
    is_sale                 tinyint(1)                              not null,
    discount_percentage     double(255, 2)                          not null,
    discount_special        double(255, 2)                          not null,
    discount_percentage_per double(255, 2)                          not null,
    remarks                 text                                    not null,
    cash_sale               varchar(255)                            not null,
    sales_man_id            int unsigned                            null,
    address                 text                                    not null,
    constraint sales_party_id_foreign
        foreign key (party_id) references parties (id),
    constraint sales_sales_man_id_foreign
        foreign key (sales_man_id) references users (id),
    constraint sales_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table stock_infos
(
    id         int unsigned auto_increment
        primary key,
    name       varchar(255)                            not null,
    location   text                                    not null,
    status     varchar(255)                            not null,
    branch_id  int unsigned                            not null,
    user_id    int unsigned                            not null,
    created_at timestamp default CURRENT_TIMESTAMP not null,
    updated_at timestamp default CURRENT_TIMESTAMP not null,
    deleted_at timestamp                               null,
    constraint stock_infos_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint stock_infos_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table purchase_invoice_details
(
    id                int unsigned auto_increment
        primary key,
    product_id        int unsigned                            not null,
    detail_invoice_id varchar(255)                            not null,
    quantity          int                                     not null,
    price             double(8, 2)                            not null,
    remarks           text                                    not null,
    created_at        timestamp default CURRENT_TIMESTAMP not null,
    updated_at        timestamp default CURRENT_TIMESTAMP not null,
    deleted_at        timestamp                               null,
    branch_id         int unsigned                            not null,
    stock_info_id     int unsigned                            not null,
    product_type      varchar(255)                            not null,
    constraint purchase_invoice_details_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint purchase_invoice_details_product_id_foreign
        foreign key (product_id) references products (id),
    constraint purchase_invoice_details_stock_info_id_foreign
        foreign key (stock_info_id) references stock_infos (id)
)
    collate = utf8_unicode_ci;

create table sale_details
(
    id            int unsigned auto_increment
        primary key,
    product_id    int unsigned                            not null,
    invoice_id    varchar(255)                            not null,
    quantity      int                                     not null,
    price         double(8, 2)                            not null,
    remarks       text                                    not null,
    created_at    timestamp default CURRENT_TIMESTAMP not null,
    updated_at    timestamp default CURRENT_TIMESTAMP not null,
    deleted_at    timestamp                               null,
    branch_id     int unsigned                            not null,
    stock_info_id int unsigned                            not null,
    product_type  varchar(255)                            not null,
    constraint sale_details_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint sale_details_product_id_foreign
        foreign key (product_id) references products (id),
    constraint sale_details_stock_info_id_foreign
        foreign key (stock_info_id) references stock_infos (id)
)
    collate = utf8_unicode_ci;

create table sales_return
(
    id               int unsigned auto_increment
        primary key,
    branch_id        int unsigned                            not null,
    party_id         int unsigned                            not null,
    product_id       int unsigned                            not null,
    cus_ref_no       varchar(255)                            not null,
    consignment_name varchar(255)                            not null,
    quantity         int                                     not null,
    return_amount    double(8, 2)                            not null,
    remarks          text                                    not null,
    created_at       timestamp default CURRENT_TIMESTAMP not null,
    updated_at       timestamp default CURRENT_TIMESTAMP not null,
    to_stock_info_id int unsigned                            not null,
    constraint sales_return_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint sales_return_party_id_foreign
        foreign key (party_id) references parties (id),
    constraint sales_return_product_id_foreign
        foreign key (product_id) references products (id),
    constraint sales_return_to_stock_info_id_foreign
        foreign key (to_stock_info_id) references stock_infos (id)
)
    collate = utf8_unicode_ci;

create table sales_return_details
(
    id               int unsigned auto_increment
        primary key,
    product_type     varchar(255)                            not null,
    product_id       int unsigned                            not null,
    quantity         int                                     not null,
    unit_price       double(8, 2)                            not null,
    return_amount    double(8, 2)                            not null,
    consignment_name varchar(255)                            not null,
    remarks          text                                    not null,
    invoice_id       varchar(255)                            not null,
    created_at       timestamp default CURRENT_TIMESTAMP not null,
    updated_at       timestamp default CURRENT_TIMESTAMP not null,
    deleted_at       timestamp                               null,
    stock_info_id    int unsigned                            null,
    constraint sales_return_details_product_id_foreign
        foreign key (product_id) references products (id),
    constraint sales_return_details_stock_info_id_foreign
        foreign key (stock_info_id) references stock_infos (id)
)
    collate = utf8_unicode_ci;

create table sales_return_invoices
(
    id                  int unsigned auto_increment
        primary key,
    branch_id           int unsigned                            not null,
    party_id            int unsigned                            not null,
    product_status      varchar(255)                            not null,
    ref_no              varchar(255)                            not null,
    discount_percentage double(255, 2)                          not null,
    user_id             int unsigned                            not null,
    invoice_id          varchar(255)                            not null,
    created_at          timestamp default CURRENT_TIMESTAMP not null,
    updated_at          timestamp default CURRENT_TIMESTAMP not null,
    to_stock_info_id    int unsigned                            null,
    stock_invoice_id    text                                    not null,
    constraint sales_return_invoices_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint sales_return_invoices_party_id_foreign
        foreign key (party_id) references parties (id),
    constraint sales_return_invoices_to_stock_info_id_foreign
        foreign key (to_stock_info_id) references stock_infos (id),
    constraint sales_return_invoices_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table stock_counts
(
    id               int unsigned auto_increment
        primary key,
    product_id       int unsigned                            not null,
    stock_info_id    int unsigned                            not null,
    product_quantity int                                     not null,
    created_at       timestamp default CURRENT_TIMESTAMP not null,
    updated_at       timestamp default CURRENT_TIMESTAMP not null,
    total_price      double(20, 2)                           not null,
    constraint stock_counts_product_id_foreign
        foreign key (product_id) references products (id),
    constraint stock_counts_stock_info_id_foreign
        foreign key (stock_info_id) references stock_infos (id)
)
    collate = utf8_unicode_ci;

create table stock_details
(
    id               int unsigned auto_increment
        primary key,
    branch_id        int unsigned                            not null,
    stock_info_id    int unsigned                            not null,
    entry_type       varchar(255)                            not null,
    product_type     varchar(255)                            not null,
    product_id       int unsigned                            not null,
    quantity         int                                     not null,
    consignment_name varchar(255)                            not null,
    to_stock_info_id int unsigned                            not null,
    invoice_id       varchar(255)                            not null,
    remarks          text                                    not null,
    created_at       timestamp default CURRENT_TIMESTAMP not null,
    updated_at       timestamp default CURRENT_TIMESTAMP not null,
    deleted_at       timestamp                               null,
    price            double                                  not null,
    constraint stock_details_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint stock_details_product_id_foreign
        foreign key (product_id) references products (id),
    constraint stock_details_stock_info_id_foreign
        foreign key (stock_info_id) references stock_infos (id),
    constraint stock_details_to_stock_info_id_foreign
        foreign key (to_stock_info_id) references stock_infos (id)
)
    collate = utf8_unicode_ci;

create table stock_invoices
(
    id           int unsigned auto_increment
        primary key,
    branch_id    int unsigned                            not null,
    status       varchar(255)                            not null,
    user_id      int unsigned                            not null,
    invoice_id   varchar(255)                            not null,
    created_at   timestamp default CURRENT_TIMESTAMP not null,
    updated_at   timestamp default CURRENT_TIMESTAMP not null,
    confirmation int                                     not null,
    remarks      text                                    not null,
    constraint stock_invoices_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint stock_invoices_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table stock_requisitions
(
    id                   int unsigned auto_increment
        primary key,
    party_id             int unsigned                            not null,
    requisition_id       varchar(255)                            not null,
    product_id           int unsigned                            not null,
    requisition_quantity int                                     not null,
    issued_quantity      int                                     not null,
    remarks              varchar(255)                            not null,
    status               varchar(255)                            not null,
    user_id              int unsigned                            not null,
    created_at           timestamp default CURRENT_TIMESTAMP not null,
    updated_at           timestamp default CURRENT_TIMESTAMP not null,
    branch_id            int unsigned                            not null,
    constraint stock_requisitions_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint stock_requisitions_party_id_foreign
        foreign key (party_id) references parties (id),
    constraint stock_requisitions_product_id_foreign
        foreign key (product_id) references products (id),
    constraint stock_requisitions_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table stocks
(
    id               int unsigned auto_increment
        primary key,
    product_id       int unsigned                            not null,
    stock_info_id    int unsigned                            not null,
    product_quantity int                                     not null,
    entry_type       varchar(255)                            not null,
    status           varchar(255)                            not null,
    remarks          text                                    not null,
    consignment_name varchar(255)                            not null,
    user_id          int unsigned                            not null,
    created_at       timestamp default CURRENT_TIMESTAMP not null,
    updated_at       timestamp default CURRENT_TIMESTAMP not null,
    to_stock_info_id int                                     null,
    product_type     varchar(255)                            not null,
    branch_id        int unsigned                            not null,
    constraint stocks_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint stocks_product_id_foreign
        foreign key (product_id) references products (id),
    constraint stocks_stock_info_id_foreign
        foreign key (stock_info_id) references stock_infos (id),
    constraint stocks_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;

create table transactions
(
    id                  int unsigned auto_increment
        primary key,
    invoice_id          varchar(255)                            not null,
    amount              double(8, 2)                            not null,
    type                varchar(255)                            not null,
    payment_method      varchar(255)                            not null,
    account_category_id int unsigned                            not null,
    remarks             text                                    not null,
    account_name_id     int unsigned                            not null,
    user_id             int unsigned                            not null,
    created_at          timestamp default CURRENT_TIMESTAMP not null,
    updated_at          timestamp default CURRENT_TIMESTAMP not null,
    deleted_at          timestamp                               null,
    cheque_no           varchar(255)                            not null,
    branch_id           int unsigned                            not null,
    cheque_date         varchar(255)                            not null,
    cheque_bank         varchar(255)                            not null,
    cheque_status       double                                  not null,
    voucher_id          varchar(255)                            not null,
    constraint transactions_account_category_id_foreign
        foreign key (account_category_id) references account_categories (id),
    constraint transactions_account_name_id_foreign
        foreign key (account_name_id) references name_of_accounts (id),
    constraint transactions_branch_id_foreign
        foreign key (branch_id) references branches (id),
    constraint transactions_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8_unicode_ci;


INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2015_05_22_122819_create_branches_tables', 1),
('2015_05_23_123344_create_product_categories_tables', 1),
('2015_05_23_212513_create_product_sub_categories_tables', 1),
('2015_05_28_195200_create_products_tables', 1),
('2015_05_31_194411_create_imports_tables', 1),
('2015_06_01_191955_create_import_details_tables', 1),
('2015_06_13_104339_create_bank_costs_tables', 1),
('2015_06_13_193759_create_cnf_costs_tables', 1),
('2015_06_13_211629_create_proforma_invoices_tables', 1),
('2015_06_27_191916_create_other_costs_tables', 1),
('2015_07_07_203324_add_product_type_to_products_table', 1),
('2015_07_07_202823_create_stock_infos_table', 2),
('2015_07_08_202823_create_stocks_tables', 3),
('2015_07_12_210429_create_parties_tables', 4),
('2015_07_12_211429_create_stock_requisitions_tables', 4),
('2015_08_30_201254_create_account_categories_table', 4),
('2015_08_31_144823_create_name_of_accounts_table', 4),
('2015_08_31_212904_add_opening_balance_to_name_of_accounts', 4),
('2015_09_03_143246_create_purchase_invoices_table', 4),
('2015_09_03_143841_create_purchase_invoice_details_table', 4),
('2015_09_11_205307_create_transactions_table', 4),
('2015_09_13_193922_create_expenses_table', 4),
('2015_09_14_205612_create_sales_table', 4),
('2015_09_14_205814_create_sale_details_table', 4),
('2015_09_27_134822_add_branch_id_to_users', 4),
('2015_09_29_182355_add_product_type_And_stock_info_id_to_stocks', 4),
('2015_10_18_212105_drop_sub_category_id_to_products', 4),
('2015_10_26_190236_add_lc_date_to_bank_costs', 5),
('2015_10_26_200209_add_clearing_date_to_cnf_costs', 5),
('2015_10_28_135515_add_branch_id_to_stocks', 5),
('2015_11_05_195857_create_stock_counts_table', 5),
('2015_11_16_180334_add_branch_stock_type_to_sale_details', 5),
('2015_11_19_201816_add_is_sale_to_sales', 5),
('2015_11_21_094816_add_cheque_no_to_transactions', 5),
('2015_11_23_140320_add_branch_stock_type_to_purchase_invoice_details', 5),
('2015_11_26_180726_add_branch_to_stock_requisitions', 5),
('2015_11_30_180055_create_sales_return_table', 5),
('2015_11_30_191341_add_branch_to_expenses', 5),
('2015_11_30_192127_add_branch_to_transactions', 5),
('2015_12_02_183325_create_balance_transfers_table', 5),
('2015_12_02_205801_add_branch_to_name_of_accounts', 5),
('2015_12_12_193013_add_cheque_date_to_transactions', 5),
('2015_12_12_193752_add_cheque_bank_to_transactions', 5),
('2015_12_12_204218_add_price_to_products', 5),
('2015_12_14_145533_drop_invoice_id_to_sales', 6),
('2015_12_14_150455_drop_invoice_id_to_purchase_invoices', 7),
('2015_12_14_150705_drop_invoice_id_to_transactions', 7),
('2015_12_14_151318_drop_invoice_id_to_sale_details', 7),
('2015_12_14_151539_drop_invoice_id_to_purchase_invoice_details', 7),
('2015_12_14_151807_drop_invoice_id_to_expenses', 7),
('2015_12_16_111703_add_cheque_status_to_transactions', 7),
('2016_03_01_024933_create_stock_invoices_table', 7),
('2016_03_01_231729_create_stock_detals', 7),
('2016_03_04_013444_create_sales_return_invoices', 7),
('2016_03_04_013804_create_sales_return_details', 7),
('2016_03_10_000617_add_stock_in_status_to_import_details_table', 7),
('2016_03_10_023529_create_tt_charges_table', 7),
('2016_03_15_014909_add_discount_percentage_to_sales_table', 7),
('2016_03_25_012731_add_discount_to_sales_table', 7),
('2016_03_26_235516_add_confirmation_to_stock_invoices_table', 7),
('2016_03_27_002350_add_remarks_to_stock_invoices_table', 7),
('2016_03_27_002935_add_remarks_to_sales_table', 7),
('2016_03_31_233256_add_to_stock_info_id_to_sales_return_table', 7),
('2016_03_31_234018_add_to_stock_info_id_to_sales_return_invoices_table', 7),
('2016_04_01_000723_add_to_stock_invoice_id_to_sales_return_invoices_table', 7),
('2017_01_02_015548_add_balance_to_parties_table', 7),
('2017_01_05_020001_add_cash_sale_to_sales_table', 7),
('2017_01_09_020933_add_sales_man_id_to_sales_table', 7),
('2017_01_12_013833_add_min_level_to_products_table', 7),
('2017_01_17_140007_add_price_to_stock_details_table', 7),
('2017_01_17_162044_add_total_price_to_stock_counts_table', 7),
('2017_01_20_022733_add_voucher_id_to_transactions_table', 7),
('2017_03_05_020938_add_stock_info_id_to_sales_return_details_table', 7),
('2017_03_07_014803_add_address_to_stocksales_table', 7);

INSERT INTO `branches` (`id`, `name`, `location`, `description`, `status`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Main Branch', 'Bangladesh', 'Main Branch of Bangladesh', 'Activate', 1, '2020-04-12 10:22:01', '2020-04-12 10:22:01', NULL);

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `role`, `address`, `sex`, `status`, `deleted_at`, `created_at`, `updated_at`, `branch_id`) VALUES
(1, 'Admin', 'admin', 'admin@email.com', '$2y$10$ZDIGvmWviW46PylmJH5fne/VKarXsauYNjj4Y72Z7wBa9/18fVjtC', '01675550199', 'admin', 'Bangladesh', 'm', 'Activate', NULL, '2020-04-12 10:24:31', '2020-04-12 10:24:31', 1);


INSERT INTO `account_categories` VALUES (1,'Sales Return Category',1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP);

INSERT INTO `name_of_accounts` VALUES (1,'Sales Return Account',1,1,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,0.00,1);