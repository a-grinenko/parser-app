# File Parsing Application

Hello! Thank you for the task—it was an interesting challenge. I wanted provide a quick overview of my thought process and the solutions handled in this implementation.

## Tech Stack Decisions

I decided against using a full framework like Laravel for this specific task. To demonstrate the solution clearly and efficiently, I primarily needed a robust Command line interface and Dependency Injection container. Since Laravel utilizes Symfony components under the hood, I opted to use `symfony/console` and `symfony/dependency-injection` directly. This keeps the project lightweight while maintaining high code quality standards.

## Key Requirements & approach

During the implementation, I focused on two main technical constraints:

1.  **Memory Management**: Since file parsing often involves large datasets, memory efficiency is critical. I've implemented a generator-based approach to read files line-by-line, preventing memory leaks associated with loading entire files into memory.
2.  **Extensibility**: I designed the parsing system to be flexible. The architecture allows for easy extension to support different file types (e.g., switching from CSV to Excel or XML) and mapping to various entities in the future.

## Architecture

I've structured the project to ensure separation of concerns:

*   **Command (`ParseFileCommand`)**: acts as the entry point handling user input.
*   **Service (`FileIterator`)**: Orchestrates the iteration process using generators.
*   **Parsers**: Designed to handle specific file formats.
*   **Builders (`UserBuilder`)**: Encapsulates the business logic for parsing raw data into structured objects.

I have also left comments and TODOs throughout the code to assist with the review process.

## How to Run

1.  **Start the project:**
    ```bash
    docker compose up -d
    ```

2.  **Run the parser:**
    ```bash
    php bin/console parse:files examples.csv --skip-first-row
    ```

> You can start reviewing the code from `src/Command/ParseFileCommand.php`.

## Closing Notes

I spent approximately one hour on this implementation.

One aspect I would improve with more time is **Testing**. I prioritized the functionality and architecture within the time limit, so I didn't include Unit or Integration tests in this iteration. However, in a production environment, full test coverage would be a priority.

Thank you for your time and feedback!
