<?php

namespace App\Http\Controllers;

use App\Models\Node;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    public function index()
    {
        $nodes = Node::latest()->get();
        return view('nodes.index', compact('nodes'));
    }

    public function create()
    {
        return view('nodes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status'   => 'required|in:active,inactive',
        ]);
        Node::create($request->all());
        return redirect()->route('nodes.index')->with('success', 'Node berhasil ditambahkan!');
    }

    public function edit(Node $node)
    {
        return view('nodes.edit', compact('node'));
    }

    public function update(Request $request, Node $node)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status'   => 'required|in:active,inactive',
        ]);
        $node->update($request->all());
        return redirect()->route('nodes.index')->with('success', 'Node berhasil diperbarui!');
    }

    public function destroy(Node $node)
    {
        $node->delete();
        return redirect()->route('nodes.index')->with('success', 'Node berhasil dihapus!');
    }
}
