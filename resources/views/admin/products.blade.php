@include('admin.navigation')

<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <input type="text" id="productFilter" placeholder="Search by product name" onkeyup="filterProducts()">
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <h1>Products</h1>
            <ul class="list-group">
                @foreach($products as $product)
                    <li class="list-group-item p">
                        <h3 class='asm'>{{ $product->name }}</h3>
                        <p>{{ $product->description }}</p>
                        <div class="buttons">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            <h1>Add Product</h1>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Product Description</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>
</div>
<br><br><br><br><br><br>
@include('admin.down')
<script>
    function filterProducts() {
        const input = document.getElementById('productFilter').value.toLowerCase();
        const productItems = document.getElementsByClassName('p');

        for (let i = 0; i < productItems.length; i++) {
            const productName = productItems[i].querySelector('.asm').textContent.toLowerCase();
            if (productName.includes(input)) {
                productItems[i].style.display = "block";
            } else {
                productItems[i].style.display = "none";
            }
        }
    }
</script>