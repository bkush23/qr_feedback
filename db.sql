create database if not exist qr_feedback_company;
       use qr_feedback_company;
create table credentials
(
    company_name       varchar(255) not null,
    company_email      varchar(255) not null
        primary key,
    company_password   varchar(255) not null,
    company_contact_no varchar(15)  not null,
    company_category   varchar(20)  not null,
    company_address    varchar(255) not null,
    company_website    varchar(255) not null,
    constraint credentials_uk
        unique (company_name, company_website)
);

create table company_events
(
    company_name       varchar(255) not null,
    event_name         varchar(255) not null,
    event_location     varchar(255) not null,
    event_type         varchar(255) not null,
    event_is_public    boolean      not null,
    event_id           varchar(255) not null primary key,
    event_date         datetime     not null,
    event_created_date datetime     not null
);

create schema events_feedback;