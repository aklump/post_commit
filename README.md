# Post Commit

![post_commit](images/screenshot.jpg)

## Summary



**Visit <https://aklump.github.io/post_commit> for full documentation.**

## Quick Start

- Install in your repository root using `cloudy pm-install aklump/post_commit`
- Open _bin/config/post_commit.yml_ and modify as needed.
- Open _bin/config/post_commit.local.yml_ and ...

## Requirements

You must have [Cloudy](https://github.com/aklump/cloudy) installed on your system to install this package.

## Installation

The installation script above will generate the following structure where `.` is your repository root.

    .
    ├── bin
    │   ├── post_commit -> ../opt/post_commit/post_commit.sh
    │   └── config
    │       ├── post_commit.yml
    │       └── post_commit.local.yml
    ├── opt
    │   ├── cloudy
    │   └── aklump
    │       └── post_commit
    └── {public web root}

    
### To Update

- Update to the latest version from your repo root: `cloudy pm-update aklump/post_commit`

## Configuration Files

| Filename | Description | VCS |
|----------|----------|---|
| _post_commit.yml_ | Configuration shared across all server environments: prod, staging, dev  | yes |
| _post_commit.local.yml_ | Configuration overrides for a single environment; not version controlled. | no |

### Custom Configuration

* lorem
* ipsum

## Usage

* To see all commands use `./bin/post_commit help`

## Contributing

If you find this project useful... please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4E5KZHDQCEUV8&item_name=Gratitude%20for%20aklump%2Fpost_commit).
