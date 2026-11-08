<?php

namespace App\Controllers;

use App\Models\BookModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class BookController extends BaseController
{
    private BookModel $books;
    private array $categories = ['Fiction', 'Non-Fiction', 'Science', 'Technology', 'History', 'Biography', 'Children', 'Other'];
    private string $dir;

    public function __construct()
    {
        $this->books = new BookModel();
        $this->dir   = FCPATH . 'uploads/books/';
    }

    private function findBook(int $id): array
    {
        $book = $this->books->find($id);   // soft-deleted rows are excluded
        if (! $book) {
            throw PageNotFoundException::forPageNotFound('Book not found');
        }
        return $book;
    }

    // ---------- Validation rules and messages (shared by store and update) ----------
    private function validationSet(bool $photoRequired, bool $photoSent): array
    {
        $year  = (int) date('Y');
        $rules = [
            'title'          => 'required|min_length[2]|max_length[150]',
            'author'         => 'required|min_length[3]|max_length[100]',
      'isbn' => 'required|min_length[10]|max_length[13]|alpha_numeric',
            'category'       => 'required|in_list[' . implode(',', $this->categories) . ']',
            'published_year' => "required|integer|greater_than_equal_to[1000]|less_than_equal_to[{$year}]",
            'published_date' => 'required|valid_date[Y-m-d]',
            'price'          => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100000]',
            'description'    => 'permit_empty|max_length[1000]',
        ];

        $messages = [
            'title' => [
                'required'   => 'Please enter the book title.',
                'min_length' => 'Title must be at least 2 characters.',
                'max_length' => 'Title cannot exceed 150 characters.',
            ],
            'author' => [
                'required'   => 'Please enter the author name.',
                'min_length' => 'Author name must be at least 3 characters.',
                'max_length' => 'Author name cannot exceed 100 characters.',
            ],
            'isbn' => [
                'required'    => 'ISBN is required.',
                'regex_match' => 'ISBN must be exactly 10 or 13 digits (numbers only).',
            ],
            'category' => [
                'required' => 'Please select a category.',
                'in_list'  => 'Please choose a valid category.',
            ],
            'published_year' => [
                'required'              => 'Published year is required.',
                'integer'               => 'Published year must be a whole number.',
                'greater_than_equal_to' => 'Published year must be 1000 or later.',
                'less_than_equal_to'    => "Published year cannot be later than {$year}.",
            ],
            'published_date' => [
                'required'   => 'Published date is required.',
                'valid_date' => 'Please enter a valid date.',
            ],
            'price' => [
                'required'              => 'Price is required.',
                'numeric'               => 'Price must be a number.',
                'greater_than_equal_to' => 'Price cannot be negative.',
                'less_than_equal_to'    => 'Price cannot exceed 100000.',
            ],
            'description' => [
                'max_length' => 'Description cannot exceed 1000 characters.',
            ],
        ];

        if ($photoRequired || $photoSent) {
            $rules['cover_photo'] = ($photoRequired ? 'uploaded[cover_photo]|' : '')
                . 'is_image[cover_photo]|mime_in[cover_photo,image/jpg,image/jpeg,image/png]|max_size[cover_photo,2048]';
            $messages['cover_photo'] = [
                'uploaded' => 'Please upload a book cover photo.',
                'is_image' => 'The file must be an image.',
                'mime_in'  => 'Only JPG, JPEG and PNG images are allowed.',
                'max_size' => 'Image size must not exceed 2 MB.',
            ];
        }

        return [$rules, $messages];
    }

    // ISBN uniqueness that ignores soft-deleted books
    private function isbnTaken(string $isbn, ?int $ignoreId = null): bool
    {
        $q = $this->books->where('isbn', $isbn);
        if ($ignoreId) {
            $q->where('id !=', $ignoreId);
        }
        return $q->first() !== null;
    }

    private function failBack(array $errors)
    {
        return redirect()->back()->withInput()
            ->with('errors', $errors)
            ->with('error', 'Please fix the highlighted fields.');
    }

    // ---------- INDEX ----------
    public function index()
    {
        $q = trim((string) $this->request->getGet('q'));

        if ($q !== '') {
            $this->books->groupStart()
                ->like('title', $q)->orLike('author', $q)->orLike('isbn', $q)
                ->groupEnd();
        }

        $list = $this->books->orderBy('id', 'DESC')->paginate(4);

        return view('book/index', [
            'books' => $list,
            'pager' => $this->books->pager,
            'q'     => $q,
        ]);
    }

    // ---------- CREATE ----------
    public function create()
    {
        return view('book/create', ['categories' => $this->categories]);
    }

    public function store()
    {
        $file = $this->request->getFile('cover_photo');
        [$rules, $messages] = $this->validationSet(true, false);

        $errors = [];
        if (! $this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();
        }
        if (! isset($errors['isbn']) && $this->isbnTaken((string) $this->request->getPost('isbn'))) {
            $errors['isbn'] = 'A book with this ISBN already exists.';
        }
        if ($errors) {
            return $this->failBack($errors);
        }

        $photo = $file->getRandomName();
        $file->move($this->dir, $photo);

        $this->books->insert([
            'title'          => $this->request->getPost('title'),
            'author'         => $this->request->getPost('author'),
            'isbn'           => $this->request->getPost('isbn'),
            'category'       => $this->request->getPost('category'),
            'published_year' => $this->request->getPost('published_year'),
            'published_date' => $this->request->getPost('published_date'),
            'price'          => $this->request->getPost('price'),
            'description'    => $this->request->getPost('description'),
            'cover_photo'    => $photo,
        ]);

        return redirect()->to('/book')->with('success', 'Book created successfully.');
    }

    // ---------- SHOW ----------
    public function show(int $id)
    {
        return view('book/show', ['book' => $this->findBook($id)]);
    }

    // ---------- EDIT / UPDATE ----------
    public function edit(int $id)
{
    return view('book/edit', [
        'book'       => $this->findBook($id),
        'categories' => $this->categories,
        'isEdit'     => true,
    ]);
}

    public function update(int $id)
    {
        $book     = $this->findBook($id);
        $file     = $this->request->getFile('cover_photo');
        $hasImage = $file && $file->getError() !== UPLOAD_ERR_NO_FILE;

        [$rules, $messages] = $this->validationSet(false, $hasImage);

        $errors = [];
        if (! $this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();
        }
        if (! isset($errors['isbn']) && $this->isbnTaken((string) $this->request->getPost('isbn'), $id)) {
            $errors['isbn'] = 'Another book already uses this ISBN.';
        }
        if ($errors) {
            return $this->failBack($errors);
        }

        $data = [
            'title'          => $this->request->getPost('title'),
            'author'         => $this->request->getPost('author'),
            'isbn'           => $this->request->getPost('isbn'),
            'category'       => $this->request->getPost('category'),
            'published_year' => $this->request->getPost('published_year'),
            'published_date' => $this->request->getPost('published_date'),
            'price'          => $this->request->getPost('price'),
            'description'    => $this->request->getPost('description'),
        ];

        if ($hasImage) {
            $photo = $file->getRandomName();
            $file->move($this->dir, $photo);

            $old = $this->dir . $book['cover_photo'];
            if ($book['cover_photo'] && is_file($old)) {
                unlink($old);
            }
            $data['cover_photo'] = $photo;
        }

        $this->books->update($id, $data);

        return redirect()->to('/book')->with('success', 'Book updated successfully.');
    }

    // ---------- SOFT DELETE ----------
    public function delete(int $id)
    {
        $this->findBook($id);
        $this->books->delete($id);   // sets deleted_at; the photo is kept for restore

        return redirect()->to('/book')->with('success', 'Book deleted successfully.');
    }

    // ---------- TRASH ----------
    public function trash()
    {
        $list = $this->books->onlyDeleted()->orderBy('deleted_at', 'DESC')->paginate(8);

        return view('book/trash', ['books' => $list, 'pager' => $this->books->pager]);
    }

    public function restore(int $id)
    {
        // builder() bypasses allowedFields and the soft-delete filter
        $this->books->builder()->where('id', $id)->update(['deleted_at' => null]);

        return redirect()->to('/book/trash')->with('success', 'Book restored successfully.');
    }
}