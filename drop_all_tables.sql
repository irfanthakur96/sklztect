-- Drop all existing tables in the wordpress database
-- This will clear the database for a fresh import

-- Drop tables with the old timestamped prefix
DROP TABLE IF EXISTS `1757397176_wp_actionscheduler_actions`;
DROP TABLE IF EXISTS `1757397176_wp_actionscheduler_claims`;
DROP TABLE IF EXISTS `1757397176_wp_actionscheduler_groups`;
DROP TABLE IF EXISTS `1757397176_wp_actionscheduler_logs`;
DROP TABLE IF EXISTS `1757397176_wp_commentmeta`;
DROP TABLE IF EXISTS `1757397176_wp_comments`;
DROP TABLE IF EXISTS `1757397176_wp_links`;
DROP TABLE IF EXISTS `1757397176_wp_options`;
DROP TABLE IF EXISTS `1757397176_wp_postmeta`;
DROP TABLE IF EXISTS `1757397176_wp_posts`;
DROP TABLE IF EXISTS `1757397176_wp_term_relationships`;
DROP TABLE IF EXISTS `1757397176_wp_term_taxonomy`;
DROP TABLE IF EXISTS `1757397176_wp_termmeta`;
DROP TABLE IF EXISTS `1757397176_wp_terms`;
DROP TABLE IF EXISTS `1757397176_wp_usermeta`;
DROP TABLE IF EXISTS `1757397176_wp_users`;

-- Drop tables with the other timestamped prefix
DROP TABLE IF EXISTS `1757397954_wp_actionscheduler_actions`;
DROP TABLE IF EXISTS `1757397954_wp_actionscheduler_claims`;
DROP TABLE IF EXISTS `1757397954_wp_actionscheduler_groups`;
DROP TABLE IF EXISTS `1757397954_wp_actionscheduler_logs`;
DROP TABLE IF EXISTS `1757397954_wp_commentmeta`;
DROP TABLE IF EXISTS `1757397954_wp_comments`;
DROP TABLE IF EXISTS `1757397954_wp_links`;
DROP TABLE IF EXISTS `1757397954_wp_options`;
DROP TABLE IF EXISTS `1757397954_wp_postmeta`;
DROP TABLE IF EXISTS `1757397954_wp_posts`;
DROP TABLE IF EXISTS `1757397954_wp_term_relationships`;
DROP TABLE IF EXISTS `1757397954_wp_term_taxonomy`;
DROP TABLE IF EXISTS `1757397954_wp_termmeta`;
DROP TABLE IF EXISTS `1757397954_wp_terms`;
DROP TABLE IF EXISTS `1757397954_wp_usermeta`;
DROP TABLE IF EXISTS `1757397954_wp_users`;

-- Drop tables with the latest timestamped prefix
DROP TABLE IF EXISTS `1761268504_wp_actionscheduler_actions`;
DROP TABLE IF EXISTS `1761268504_wp_actionscheduler_claims`;
DROP TABLE IF EXISTS `1761268504_wp_actionscheduler_groups`;
DROP TABLE IF EXISTS `1761268504_wp_actionscheduler_logs`;
DROP TABLE IF EXISTS `1761268504_wp_commentmeta`;
DROP TABLE IF EXISTS `1761268504_wp_comments`;
DROP TABLE IF EXISTS `1761268504_wp_links`;
DROP TABLE IF EXISTS `1761268504_wp_options`;
DROP TABLE IF EXISTS `1761268504_wp_postmeta`;
DROP TABLE IF EXISTS `1761268504_wp_posts`;
DROP TABLE IF EXISTS `1761268504_wp_term_relationships`;
DROP TABLE IF EXISTS `1761268504_wp_term_taxonomy`;
DROP TABLE IF EXISTS `1761268504_wp_termmeta`;
DROP TABLE IF EXISTS `1761268504_wp_terms`;
DROP TABLE IF EXISTS `1761268504_wp_usermeta`;
DROP TABLE IF EXISTS `1761268504_wp_users`;

-- Drop tables with the other latest timestamped prefix
DROP TABLE IF EXISTS `1761268175_wp_actionscheduler_actions`;
DROP TABLE IF EXISTS `1761268175_wp_actionscheduler_claims`;
DROP TABLE IF EXISTS `1761268175_wp_actionscheduler_groups`;
DROP TABLE IF EXISTS `1761268175_wp_actionscheduler_logs`;
DROP TABLE IF EXISTS `1761268175_wp_commentmeta`;
DROP TABLE IF EXISTS `1761268175_wp_comments`;
DROP TABLE IF EXISTS `1761268175_wp_links`;
DROP TABLE IF EXISTS `1761268175_wp_options`;
DROP TABLE IF EXISTS `1761268175_wp_postmeta`;
DROP TABLE IF EXISTS `1761268175_wp_posts`;
DROP TABLE IF EXISTS `1761268175_wp_term_relationships`;
DROP TABLE IF EXISTS `1761268175_wp_term_taxonomy`;
DROP TABLE IF EXISTS `1761268175_wp_termmeta`;
DROP TABLE IF EXISTS `1761268175_wp_terms`;
DROP TABLE IF EXISTS `1761268175_wp_usermeta`;
DROP TABLE IF EXISTS `1761268175_wp_users`;

-- Drop any other tables that might exist
DROP TABLE IF EXISTS `wp_actionscheduler_actions`;
DROP TABLE IF EXISTS `wp_actionscheduler_claims`;
DROP TABLE IF EXISTS `wp_actionscheduler_groups`;
DROP TABLE IF EXISTS `wp_actionscheduler_logs`;
DROP TABLE IF EXISTS `wp_commentmeta`;
DROP TABLE IF EXISTS `wp_comments`;
DROP TABLE IF EXISTS `wp_links`;
DROP TABLE IF EXISTS `wp_options`;
DROP TABLE IF EXISTS `wp_postmeta`;
DROP TABLE IF EXISTS `wp_posts`;
DROP TABLE IF EXISTS `wp_term_relationships`;
DROP TABLE IF EXISTS `wp_term_taxonomy`;
DROP TABLE IF EXISTS `wp_termmeta`;
DROP TABLE IF EXISTS `wp_terms`;
DROP TABLE IF EXISTS `wp_usermeta`;
DROP TABLE IF EXISTS `wp_users`;

-- Show remaining tables to verify cleanup
SHOW TABLES;