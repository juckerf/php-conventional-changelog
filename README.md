# PHP Conventional Changelog

![Version](https://img.shields.io/badge/version-1.4.3-brightgreen?style=for-the-badge)
![Requirements](https://img.shields.io/badge/php-%3E%3D%207.1.3-4F5D95?style=for-the-badge)
![Code Style](https://img.shields.io/badge/code%20style-PSR-blue?style=for-the-badge)
![License](https://img.shields.io/github/license/marcocesarato/php-conventional-changelog?style=for-the-badge)
[![GitHub](https://img.shields.io/badge/GitHub-Repo-6f42c1?style=for-the-badge)](https://github.com/marcocesarato/php-conventional-changelog)

## Description

Generate changelogs from a project's commit messages and metadata using composer and automate versioning with [semver](https://semver.org) and [conventional-commits](https://conventionalcommits.org).

This package can generate changelog and release notes from committing history.
It provides a command that can be run from the terminal, or using composer scripts, to generate a changelog file in markdown for the current project.

The command may take parameters that define the releases of the project that will be considered to extract the changes from the git history to generate the file.
The package uses a configuration system with that permit to customize the settings you may want to have your desired changelog generated.

### How to contribute

Have an idea? Found a bug? Please raise to [ISSUES](https://github.com/marcocesarato/php-conventional-changelog/issues) or [PULL REQUEST](https://github.com/marcocesarato/php-conventional-changelog/pulls).
Contributions are welcome and are greatly appreciated! Every little bit helps.

## 📖 Installation

You can install it easily with composer

`composer require --dev marcocesarato/php-conventional-changelog`

#### Scripts *(Optional)*

For easy use the changelog generator or release faster your new version you can add to your `composer.json` the scripts:

> You can customize it according to your needs

```
{
  ...
  "scripts": {
    "changelog": "conventional-changelog",
    "release": "conventional-changelog --commit",
    "release:minor": "conventional-changelog --minor --commit",
    "release:major": "conventional-changelog --major --commit"
  },
  ...
}
```

Now you can just run `composer changelog` to generate your changelog.

## 📘 Configuration

> **Note:** This procedure is *optional* and permit to overwriting/merging the default settings

Create a file named `.changelog` on the root of your project or on the working dir.

> **Note:** If you don't need to customize some settings just omit it from the configuration file

> **Note:** The default ignored types are: `build`, `chore`, `ci`, `docs`, `refactor`, `revert`, `style`, `test`

> **Note:** To allow all types just keep empty `types` and set empty `ignoreTypes`

Create your configuration settings with the help of the following example.

#### Config Example
```php
<?php
return [
  // File changelog (relative to the working dir)
  'path' => 'docs/CHANGELOG.md', // You can specify a different folder
  'headerTitle' => 'My changelog',
  'headerDescription' => 'This is my changelog file.',
  // Types allowed on changelog
  'types' => ['feat', 'fix', 'pref'], // These could overwrite ignored types
  'preset' => [
    // Add improvements type (deprecated type)
    'improvements' => [
      'label' => 'Improvements',
      'description' => 'Improvements to existing features'
    ],
    'chore' => [
      // Change chore default label
      'label' => 'Others'
    ],
  ],
  // Exclude not notables types (following types are the default excluded types)
  'ignoreTypes' => ['build', 'chore', 'ci', 'docs', 'refactor', 'revert', 'style', 'test'],
  'ignorePatterns' => [
    // Exclude all commits with this message
    'chore(deps): update dependencies',
    // You can also use regex to exclude all commit like 'chore(changelog): updated'
    '/chore\(changelog\)[:].*/i'
  ],
];
```

## 💻 Usage

> **Note:** all following commands must be run (working dir) on the root of the project or in the path where the changelog should be generated

The changelog generator will generate a log of changes from the date of the last tag *(if not specified with `--from-date`)* to the current date *(if not specified with `--to-date`)*,
and it will put all commit logs in the latest version just created (at the moment it doesn't generate the entire git commit version release history).
By default, will be added one to the patch semver part *(Example, if the last version is `1.0.2` the newer, if not specified the identity of the release, will be `1.0.3`)*.

---

To generate your changelog for your first release:
  
> **Note:** If the version code (`--ver`) isn't specified it will be automatically `1.0.0`

```shell
php vendor/bin/conventional-changelog --first-release
```

---

To generate your changelog without committing files:

```shell  
php vendor/bin/conventional-changelog
```

---

To generate your changelog with auto commit and auto version tagging:

```shell
php vendor/bin/conventional-changelog --commit
```

---

To generate your changelog from a specified date to another specified date:

```shell
php vendor/bin/conventional-changelog --from-date="2020-12-01" --to-date="2021-01-01"
```

---

To generate your changelog with a specific version code:

```shell
php vendor/bin/conventional-changelog --ver="2.0.1"
```

---

To generate your changelog with the entire history of changes of all releases:

> **Warn:** This operation will overwrite the `CHANGELOG.md` file if it already exists

```shell
php vendor/bin/conventional-changelog --history
```

---

### Commands List

> You can have more info about running  `php vendor/bin/conventional-changelog --help`

```
-c      --commit          bool        Commit the new release once changelog is generated
-a      --amend           bool        Amend commit the new release once changelog is generated
        --major           bool        Major release (important changes)
        --minor           bool        Minor release (add functionality)
        --patch           bool        Patch release (bug fixes) [default]
        --rc              bool        Release Candidate
        --beta            bool        Beta release
        --alpha           bool        Alpha release
        --first-release   bool        Run at first release (if --ver isn't specified version code will be 1.0.0)
        --to-date         str         Get commits from last tag date (or specified on --from-date) to specified date [YYYY-MM-DD]
        --from-date       str         Get commits from specified date [YYYY-MM-DD]
        --ver             str         Define the next release version code (semver)
        --history         bool        Generate the entire history of changes of all releases
        --no-verify       bool        Bypasses the pre-commit and commit-msg hooks
        --no-tag          bool        Disable release auto tagging when commit enabled
```

[semver]: http://semver.org

[conventionalcommits]: https://conventionalcommits.org