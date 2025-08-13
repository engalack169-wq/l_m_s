# Library Management CLI

## Overview
This Library Management System is a command-line interface (CLI) application built in PHP that allows users to manage a library's collection of books and members. It provides functionalities for borrowing and returning books, as well as managing member information.

## Features
- **Book Management**: Add, find, and list books in the library.
- **Member Management**: Add, find, and list library members.
- **Borrowing and Returning Books**: Members can borrow and return books, with checks for availability and member status.
- **User-Friendly Menu**: A simple command-line menu interface for easy navigation.

## File Structure
```
library-management-cli
├── src
│   ├── Library.php
│   ├── Book.php
│   ├── Member.php
│   ├── BorrowManager.php
│   ├── Menu.php
│   └── helpers
│       └── InputHelper.php
├── cli.php
└── README.md
```

## Installation
1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Ensure you have PHP installed on your system.
4. Run the application using the command:
   ```
   php cli.php
   ```

## Usage
- Follow the on-screen prompts to manage books and members.
- Use the menu options to navigate through different functionalities.

## Contributing
Contributions are welcome! Please feel free to submit a pull request or open an issue for any suggestions or improvements.

## License
This project is open-source and available under the MIT License.