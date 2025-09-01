<?php

function isAdmin() {
    return session()->get('role') === 'admin';
}

function isViewer() {
    return session()->get('role') === 'viewer';
}


function requireAdmin() {
    if (session()->get('role') !== 'admin') {
        return redirect()->to('/products')->with('error', 'Unauthorized access');
    }
    return null; 
}

