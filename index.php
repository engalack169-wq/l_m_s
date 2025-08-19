declare(strict_types=1);

<?php

// ========== Book Class ==========
class Book {
    private $title;
    private $author;
    private $isbn;
    private $isAvailable;

    public function __construct(string $title, string $author, string $isbn) {
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->isAvailable = true;
    }

    public function borrowBook() {
        $this->isAvailable = false;
    }

    public function returnBook() {
        $this->isAvailable = true;
    }

    public function getBookInfo(): string {
        $status = $this->isAvailable ? "✅ Available" : "❌ Borrowed";
        return sprintf("%-25s | %-18s | %-8s | %-12s", $this->title, $this->author, $this->isbn, $status);
    }

    public function getTitle(): string { return $this->title; }
    public function getAuthor(): string { return $this->author; }
    public function getIsbn(): string { return $this->isbn; }
    public function isAvailable(): bool { return $this->isAvailable; }
}

// ========== Member Class ==========
class Member {
    private $name;
    private $memberId;
    private $borrowedBooks;

    public function __construct(string $name, string $memberId) {
        $this->name = $name;
        $this->memberId = $memberId;
        $this->borrowedBooks = [];
    }

    public function borrowBook(Book $book) {
        $this->borrowedBooks[$book->getIsbn()] = $book;
    }

    public function returnBook(Book $book) {
        unset($this->borrowedBooks[$book->getIsbn()]);
    }

    public function getBorrowedBooks(): array {
        return $this->borrowedBooks;
    }

    public function getBorrowedBooksCount(): int {
        return count($this->borrowedBooks);
    }

    public function getName(): string { return $this->name; }
    public function getMemberId(): string { return $this->memberId; }
}

// ========== Library Class ==========
class Library {
    private $books;
    private $members;

    public function __construct() {
        $this->books = [];
        $this->members = [];
    }

    public function addBook(Book $book): bool {
        if (isset($this->books[$book->getIsbn()])) {
            return false;
        }
        $this->books[$book->getIsbn()] = $book;
        return true;
    }

    public function addMember(Member $member): bool {
        if (isset($this->members[$member->getMemberId()])) {
            return false;
        }
        $this->members[$member->getMemberId()] = $member;
        return true;
    }

    public function findBook(string $isbn): ?Book {
        return $this->books[$isbn] ?? null;
    }

    public function findMember(string $memberId): ?Member {
        return $this->members[$memberId] ?? null;
    }

    public function getAllBooks(): array {
        return $this->books;
    }

    public function getAllMembers(): array {
        return $this->members;
    }

    public function getAvailableBooks(): array {
        return array_filter($this->books, fn($book) => $book->isAvailable());
    }
}

// ========== Input Helper ==========
class InputHelper {
    public static function getInput(string $prompt): string {
        echo $prompt;
        return trim(fgets(STDIN));
    }

    public static function waitForEnter() {
        echo "\nPress Enter to continue...";
        fgets(STDIN);
    }

    public static function clearScreen() {
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            system('cls');
        } else {
            system('clear');
        }
    }
}

// ========== Menu Class ==========
class Menu {
    private $library;

    public function __construct(Library $library) {
        $this->library = $library;
    }

    public function display() {
        InputHelper::clearScreen();
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║         📚 LIBRARY MANAGEMENT SYSTEM - MAIN MENU         ║\n";
        echo "╠════════════════════════════════════════════════════════════╣\n";
        echo "║ 1. View All Books                                        ║\n";
        echo "║ 2. View Available Books                                  ║\n";
        echo "║ 3. View All Members                                      ║\n";
        echo "║ 4. Add New Book                                          ║\n";
        echo "║ 5. Add New Member                                        ║\n";
        echo "║ 6. Borrow Book                                           ║\n";
        echo "║ 7. Return Book                                           ║\n";
        echo "║ 8. View Member's Borrowed Books                          ║\n";
        echo "║ 9. Search Book by ISBN                                   ║\n";
        echo "║ 0. Exit                                                  ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
    }

    public function handleChoice($choice) {
        switch ($choice) {
            case '1': $this->viewAllBooks(); break;
            case '2': $this->viewAvailableBooks(); break;
            case '3': $this->viewAllMembers(); break;
            case '4': $this->addNewBook(); break;
            case '5': $this->addNewMember(); break;
            case '6': $this->borrowBook(); break;
            case '7': $this->returnBook(); break;
            case '8': $this->viewMembersBorrowedBooks(); break;
            case '9': $this->searchBookByISBN(); break;
            case '0': return false;
            default:
                echo "❗ Invalid choice. Please try again.\n";
                InputHelper::waitForEnter();
        }
        return true;
    }

    private function viewAllBooks() {
        InputHelper::clearScreen();
        $books = $this->library->getAllBooks();
        echo "═══════════════════════════════════════════════════════════════════════════════════\n";
        echo "📚 ALL BOOKS\n";
        echo "═══════════════════════════════════════════════════════════════════════════════════\n";
        printf("%-25s | %-18s | %-8s | %-12s\n", "Title", "Author", "ISBN", "Status");
        echo str_repeat("-", 75) . "\n";
        foreach ($books as $book) {
            echo $book->getBookInfo() . "\n";
        }
        echo "═══════════════════════════════════════════════════════════════════════════════════\n";
        echo "Total Books: " . count($books) . "\n";
        InputHelper::waitForEnter();
    }

    private function viewAvailableBooks() {
        InputHelper::clearScreen();
        $books = $this->library->getAvailableBooks();
        echo "═══════════════════════════════════════════════════════════════════════════════════\n";
        echo "📗 AVAILABLE BOOKS\n";
        echo "═══════════════════════════════════════════════════════════════════════════════════\n";
        printf("%-25s | %-18s | %-8s | %-12s\n", "Title", "Author", "ISBN", "Status");
        echo str_repeat("-", 75) . "\n";
        foreach ($books as $book) {
            echo $book->getBookInfo() . "\n";
        }
        echo "═══════════════════════════════════════════════════════════════════════════════════\n";
        echo "Available Books: " . count($books) . "\n";
        InputHelper::waitForEnter();
    }

    private function viewAllMembers() {
        InputHelper::clearScreen();
        $members = $this->library->getAllMembers();
        echo "═══════════════════════════════════════════════════════════════════════════════════\n";
        echo "👤 ALL MEMBERS\n";
        echo "═══════════════════════════════════════════════════════════════════════════════════\n";
        printf("%-20s | %-8s | %-20s\n", "Name", "ID", "Borrowed Books");
        echo str_repeat("-", 55) . "\n";
        foreach ($members as $member) {
            printf("%-20s | %-8s | %-20s\n", $member->getName(), $member->getMemberId(), $member->getBorrowedBooksCount());
        }
        echo "═══════════════════════════════════════════════════════════════════════════════════\n";
        echo "Total Members: " . count($members) . "\n";
        InputHelper::waitForEnter();
    }

    private function addNewBook() {
        InputHelper::clearScreen();
        echo "📕 ADD NEW BOOK\n";
        $title = InputHelper::getInput("Enter book title: ");
        $author = InputHelper::getInput("Enter author: ");
        $isbn = InputHelper::getInput("Enter ISBN: ");
        if ($this->library->findBook($isbn)) {
            echo "❗ Book with this ISBN already exists!\n";
        } else {
            $book = new Book($title, $author, $isbn);
            $this->library->addBook($book);
            echo "✅ Book added successfully!\n";
        }
        InputHelper::waitForEnter();
    }

    private function addNewMember() {
        InputHelper::clearScreen();
        echo "👤 ADD NEW MEMBER\n";
        $name = InputHelper::getInput("Enter member name: ");
        $memberId = InputHelper::getInput("Enter member ID: ");
        if ($this->library->findMember($memberId)) {
            echo "❗ Member with this ID already exists!\n";
        } else {
            $member = new Member($name, $memberId);
            $this->library->addMember($member);
            echo "✅ Member added successfully!\n";
        }
        InputHelper::waitForEnter();
    }

    private function borrowBook() {
        InputHelper::clearScreen();
        echo "📙 BORROW BOOK\n";
        $memberId = InputHelper::getInput("Enter member ID: ");
        $member = $this->library->findMember($memberId);
        if (!$member) {
            echo "❗ Member not found!\n";
            InputHelper::waitForEnter();
            return;
        }
        $isbn = InputHelper::getInput("Enter book ISBN: ");
        $book = $this->library->findBook($isbn);
        if (!$book) {
            echo "❗ Book not found!\n";
        } elseif (!$book->isAvailable()) {
            echo "❗ Book is already borrowed!\n";
        } else {
            $book->borrowBook();
            $member->borrowBook($book);
            echo "✅ Book borrowed successfully!\n";
        }
        InputHelper::waitForEnter();
    }

    private function returnBook() {
        InputHelper::clearScreen();
        echo "📘 RETURN BOOK\n";
        $memberId = InputHelper::getInput("Enter member ID: ");
        $member = $this->library->findMember($memberId);
        if (!$member) {
            echo "❗ Member not found!\n";
            InputHelper::waitForEnter();
            return;
        }
        $isbn = InputHelper::getInput("Enter book ISBN: ");
        $book = $this->library->findBook($isbn);
        if (!$book) {
            echo "❗ Book not found!\n";
        } elseif (!isset($member->getBorrowedBooks()[$isbn])) {
            echo "❗ This member did not borrow this book!\n";
        } else {
            $book->returnBook();
            $member->returnBook($book);
            echo "✅ Book returned successfully!\n";
        }
        InputHelper::waitForEnter();
    }

    private function viewMembersBorrowedBooks() {
        InputHelper::clearScreen();
        echo "👤 MEMBER'S BORROWED BOOKS\n";
        $memberId = InputHelper::getInput("Enter member ID: ");
        $member = $this->library->findMember($memberId);
        if (!$member) {
            echo "❗ Member not found!\n";
            InputHelper::waitForEnter();
            return;
        }
        $borrowedBooks = $member->getBorrowedBooks();
        if (empty($borrowedBooks)) {
            echo "ℹ  No books borrowed by this member.\n";
        } else {
            printf("%-25s | %-18s | %-8s\n", "Title", "Author", "ISBN");
            echo str_repeat("-", 55) . "\n";
            foreach ($borrowedBooks as $book) {
                printf("%-25s | %-18s | %-8s\n", $book->getTitle(), $book->getAuthor(), $book->getIsbn());
            }
        }
        InputHelper::waitForEnter();
    }

    private function searchBookByISBN() {
        InputHelper::clearScreen();
        echo "🔎 SEARCH BOOK BY ISBN\n";
        $isbn = InputHelper::getInput("Enter ISBN: ");
        $book = $this->library->findBook($isbn);
        if (!$book) {
            echo "❗ Book not found!\n";
        } else {
            printf("%-25s | %-18s | %-8s | %-12s\n", "Title", "Author", "ISBN", "Status");
            echo str_repeat("-", 75) . "\n";
            echo $book->getBookInfo() . "\n";
        }
        InputHelper::waitForEnter();
    }
}

// ========== LibraryApp Class ==========
class LibraryApp {
    private $library;
    private $menu;

    public function __construct() {
        $this->library = new Library();
        $this->initializeData();
        $this->menu = new Menu($this->library);
    }

    private function initializeData() {
        // Sample Books
        $this->library->addBook(new Book("The Great Gatsby", "F.S. Fitzgerald", "001"));
        $this->library->addBook(new Book("1984", "George Orwell", "002"));
        $this->library->addBook(new Book("To Kill a Mockingbird", "Harper Lee", "003"));
        $this->library->addBook(new Book("Pride and Prejudice", "Jane Austen", "004"));
        // Sample Members
        $this->library->addMember(new Member("Alice Johnson", "M001"));
        $this->library->addMember(new Member("Bob Smith", "M002"));
        $this->library->addMember(new Member("Carol Davis", "M003"));
    }

    public function run() {
        while (true) {
            $this->menu->display();
            $choice = InputHelper::getInput("Enter your choice: ");
            if ($this->menu->handleChoice($choice) === false) {
                echo "\n👋 Exiting Library Management System. Goodbye!\n";
                break;
            }
        }
    }
}

// ========== Run the Application ==========
$app = new LibraryApp();
$app->run();
?>