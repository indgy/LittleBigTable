# Changelog
All notable changes to this project will be documented in this file.
 
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [0.1.0] - 2021-02-12

First dependable release, I have started using this in Production.

### Added

- New setting [headers](#adding-http-headers) to allow sending custom HTTP headers with the requests.
- New setting [args](#changing-the-url-query-arguments) to allow setting the url query argument names to suit your application.

### Changed

- The default storage key prefix is now capitalised to match the new project name, it was 'lbt' now it is 'lBt', hopefully this makes it more obvious as to what it relates to.

### Fixed

- An issue with the pager display when the total number of results was less than number of results to show 
