# 📊 SheetsGPT

**AI-Powered Spreadsheet Intelligence**

SheetsGPT is a modern web application built with **Laravel** that allows users to upload their spreadsheets (CSV, XLSX) and query them using natural language. Powered by the incredibly capable **Google Gemini/Gemma AI** models, SheetsGPT transforms static rows and columns into conversational insights.

## ✨ Features

- **Multi-File Workspaces**: Create dedicated workspaces and upload multiple `.csv` or `.xlsx` files into a single project. The AI understands the context across all your files simultaneously.
- **Natural Language Queries**: Simply ask questions in plain English—no SQL, no complex pivot tables. *"What is the total revenue by region?"* or *"Can you give me a list of customers in London?"*
- **Structured Data Extraction**: The AI responds with both natural text and structured **Markdown Tables**, ensuring the data presented back to you is clean and readable. 
- **PDF & Markdown Exports**: Easily export your AI-generated insights and tables into beautifully formatted PDF documents or copy the raw markdown instantly.
- **Smart Token Management**: Built-in context truncation safeguards ensure even massive spreadsheet dumps fit perfectly within the AI's token limit window
- **Responsive & Beautiful UI**: A highly polished, dynamic user interface utilizing GSAP animations and Tailwind CSS for an incredibly premium user experience.

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **AI Integration**: Google Gemini API Integration
- **Frontend / Styling**: Tailwind CSS, GSAP (Animations), Blade Templating
- **Database**: SQLite / MySQL (Configurable)
- **File Parsing**: Handles large scale CSV and XLSX file parsing seamlessly.

## 🔒 Security
- Data is processed securely in isolated workspaces.
- Spreadsheet contexts are meticulously truncated before API dispatch to circumvent token overflow constraints while preserving data integrity.

---
*Built with ❤️ to make spreadsheet analysis effortless.*
