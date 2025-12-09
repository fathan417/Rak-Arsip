<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use Illuminate\Support\Str;


class ArsipController extends Controller
{
    // AKSES HALAMAN
    // ================================================================
    
        public function public() {
            return view('partials.public-content');
        }

        public function adminLogin(Request $request) {
            $request->validate([
                'code' => 'required|digits:6',
            ]);
        
            if ($request->code === '123456') {
                session(['is_admin' => true]);
                return redirect()->route('dashboard.admin.view');
            }
        
            return redirect()->route('dashboard')->with('error', 'Kode admin salah');
        }

        public function adminLogout() {
            session()->forget('is_admin');
            return redirect()->route('dashboard');
        }

        public function admin() {
            if (!session('is_admin')) {
                return redirect()->route('dashboard')->with('error', 'Akses admin hanya untuk user yang login.');
            }

            $arsip = Arsip::latest()->get();

            return view('partials.admin-content', compact('arsip'));
        }

    // SEARCH ADMIN, SEARCH PUBLIC, SUGGESTION
    // ================================================================

        public function adminSearch(Request $request) {
            $q = $request->input('q');
        
            $arsip = Arsip::when($q, function($query) use ($q) {
                    $query->where('judul', 'LIKE', "%$q%")
                          ->orWhere('pengarang', 'LIKE', "%$q%");
                })
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('partials.sub-partials.admin-archive-list', compact('arsip'));
        }
    
        public function search(Request $request) {
            $query = $request->input('q');
            $kategori = $request->input('kategori', []);
        
            $arsip = Arsip::query();
        
            if ($query) {
                $arsip->where(function ($q2) use ($query) {
                    $q2->where('judul', 'LIKE', "%{$query}%")
                        ->orWhere('pengarang', 'LIKE', "%{$query}%");
                });
            }
        
            if (!empty($kategori)) {
                $arsip->whereIn('kategori', $kategori);
            }
        
            $arsip = $arsip->paginate(4)->appends([
                'q' => $query,
                'kategori' => $kategori
            ]);
        
            if ($arsip->count() === 0) {
                return response()->json([
                    'data' => view('partials.sub-partials.empty-result')->render(),
                    'pagination' => ''
                ]);
            }
        
            return response()->json([
                'data' => view('partials.sub-partials.arsip-card', compact('arsip'))->render(),
                'pagination' => (string) $arsip->links('partials.sub-partials.pagination')
            ]);
        }
    
        public function suggest(Request $request) {
            $query = $request->input('q');
        
            $results = Arsip::where('judul', 'LIKE', "%{$query}%")
                ->orWhere('pengarang', 'LIKE', "%{$query}%")
                ->take(5)
                ->get(['id', 'judul', 'pengarang']);
        
            return response()->json($results);
        }
    
    // CREATE, UPDATE, DELETE ADMIN
    // ================================================================
        public function store(Request $request) {
            if (!session('is_admin')) {
                return redirect()->route('dashboard')->with('error', 'Akses admin hanya untuk user yang login.');
            }

            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'pengarang' => 'required|string|max:255',
                'abstrak' => 'required|string',
                'lokasi_rak' => 'required|string|max:100',
                'lokasi_baris' => 'required|string|max:100',
                'kategori' => 'nullable|string|max:100',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
                'file_dokumen' => 'nullable|mimes:pdf,doc,docx,txt|max:5120',
            ]);

            $thumbnailPath = null;
            $fileDokumenPath = null;

            if ($request->hasFile('thumbnail')) {
                $thumbnailName = Str::random(40) . '.' . $request->thumbnail->extension();
                $request->thumbnail->move(public_path('storage/thumbnails'), $thumbnailName);
                        
                $thumbnailPath = 'thumbnails/' . $thumbnailName;
            }
            
            if ($request->hasFile('file_dokumen')) {
                $fileName = Str::random(40) . '.' . $request->file_dokumen->extension();
                $request->file_dokumen->move(public_path('storage/dokumen'), $fileName);
            
                $fileDokumenPath = 'dokumen/' . $fileName;
            }

            Arsip::create([
                'judul' => $validated['judul'],
                'pengarang' => $validated['pengarang'],
                'abstrak' => $validated['abstrak'],
                'lokasi_rak' => $validated['lokasi_rak'],
                'lokasi_baris' => $validated['lokasi_baris'],
                'kategori' => $validated['kategori'] ?? null,
                'thumbnail_path' => $thumbnailPath,
                'file_dokumen_path' => $fileDokumenPath,
                'slug' => Str::slug($validated['judul']),
                'published_at' => now(),
            ]);

            return redirect()->route('dashboard.admin.view')
            ->with('success', "Arsip berhasil ditambahkan.");
        }

        public function update(Request $request, $id) {
            if (!session('is_admin')) {
                return redirect()->route('dashboard')->with('error', 'Akses admin hanya untuk user yang login.');
            }

            $arsip = Arsip::findOrFail($id);

            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'pengarang' => 'required|string|max:255',
                'abstrak' => 'required|string',
                'lokasi_rak' => 'required|string|max:100',
                'lokasi_baris' => 'required|string|max:100',
                'kategori' => 'nullable|string|max:100',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
                'file_dokumen' => 'nullable|mimes:pdf,doc,docx,txt|max:5120',
            ]);

            $thumbnailPath = $arsip->thumbnail_path;
            $fileDokumenPath = $arsip->file_dokumen_path;

            if ($request->hasFile('thumbnail')) {
                if ($thumbnailPath && \Storage::disk('public')->exists($thumbnailPath)) {
                    \Storage::disk('public')->delete($thumbnailPath);
                }
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            if ($request->hasFile('file_dokumen')) {
                if ($fileDokumenPath && \Storage::disk('public')->exists($fileDokumenPath)) {
                    \Storage::disk('public')->delete($fileDokumenPath);
                }
                $fileDokumenPath = $request->file('file_dokumen')->store('dokumen', 'public');
            }

            $arsip->update([
                'judul' => $validated['judul'],
                'pengarang' => $validated['pengarang'],
                'abstrak' => $validated['abstrak'],
                'lokasi_rak' => $validated['lokasi_rak'],
                'lokasi_baris' => $validated['lokasi_baris'],
                'kategori' => $validated['kategori'] ?? null,
                'thumbnail_path' => $thumbnailPath,
                'file_dokumen_path' => $fileDokumenPath,
                'slug' => Str::slug($validated['judul']),
            ]);

            return redirect()->route('dashboard.admin.view')
            ->with('success', "Data arsip berhasil diedit.");
        }
        
        public function destroy($id) {
            $arsip = Arsip::findOrFail($id);
            $judul = $arsip->judul;

            if ($arsip->thumbnail && file_exists(public_path('storage/' . $arsip->thumbnail))) {
                unlink(public_path('storage/' . $arsip->thumbnail));
            }

            if ($arsip->dokumen && file_exists(public_path('storage/' . $arsip->dokumen))) {
                unlink(public_path('storage/' . $arsip->dokumen));
            }

            $arsip->delete();

            return redirect()->route('dashboard.admin.view')
            ->with('success', "Arsip \"{$judul}\" berhasil dihapus.");
        }

}