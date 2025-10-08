# Changelog

### 1.1.0

- Security update - visitor session ID is not persisted

- **BC ⚠️**: Changed `RequestLog::$sessionId` property to `$visitorId`
  - **BC ⚠️**: Changed `RequestLogInterface::getSessionId()` to `getVisitorId()`
  - **BC ⚠️**: Changed `RequestLogInterface::setSessionId()` to `setVisitorId()`
  - **BC ⚠️**: Column `session_id` renamed to `visitor_id` in `threebrs_request_log` table
- **BC ⚠️**: Changed SQL column type of `id` from `integer` to `BIGINT UNSIGNED` in `threebrs_request_log` table
- **BC ⚠️**: Changed `RequestLog::$createdAt` property type from `\DateTimeInterface` to `\DateTimeImmutable`