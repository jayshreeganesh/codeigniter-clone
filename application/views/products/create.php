<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Add New Product</h1>
            <p class="text-sm text-slate-500 mt-1">Create a new item in your inventory</p>
        </div>
        <a href="<?= site_url('products') ?>" class="text-sm text-slate-600 hover:text-slate-900 font-medium">
            &larr; Back to list
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8">
        <form action="<?= site_url('products/store') ?>" method="POST" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition" placeholder="e.g. Wireless Noise-Canceling Headphones">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="sku" class="block text-sm font-medium text-slate-700 mb-1">SKU Code <span class="text-red-500">*</span></label>
                    <input type="text" id="sku" name="sku" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition" placeholder="e.g. WH-1000XM5">
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Price ($) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" id="price" name="price" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition" placeholder="99.99">
                </div>
                <div>
                    <label for="stock" class="block text-sm font-medium text-slate-700 mb-1">Stock Quantity</label>
                    <input type="number" min="0" id="stock" name="stock" value="0" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition" placeholder="Detailed product specifications, features, warranty..."></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                <a href="<?= site_url('products') ?>" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>
