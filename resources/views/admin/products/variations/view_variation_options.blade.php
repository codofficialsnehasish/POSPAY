<div class="table-responsive scroll-sm">
    <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
        <thead>
            <tr>
                <th>Sl.no</th>
                <th>Name</th>
                <th>Barcode</th>
                <th>Quantity</th>
                <th>Price</th>
       
                {{-- @canany(['Permission Edit', 'Permission Delete']) --}}
                    <th>Action</th>
                {{-- @endcanany --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($options as $option)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $option->name }}</td>
                    <td>{{ $option->barcode }}</td>
                    <td>{{ $option->quantity }}
                    <td>{{ $option->price }}
          
                  
                    {{-- @canany(['Permission Edit', 'Permission Delete']) --}}
                        <td class="d-flex">
                            {{-- @can('Permission Edit') --}}
                                <button type="button"
                                    class=" bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                    style="margin-right: 10px;" onclick="edit_product_variation_option('{{$variation->id}}','{{$option->id}}')">
                                    <iconify-icon icon="lucide:edit"
                                        class="menu-icon"></iconify-icon>
                                </button>
                            {{-- @endcan --}}
                            {{-- @can('Permission Delete') --}}
                                <a class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                    href="javascript:void(0);" onclick="deleteItem(this)"
                                    data-url="{{ route('products.delete-variation-option',$option->id) }}"
                                    data-item="Option" alt="delete"> <iconify-icon
                                        icon="fluent:delete-24-regular"
                                        class="menu-icon"></iconify-icon></a>
                            {{-- @endcan --}}
                        </td>
                    {{-- @endcanany --}}

                </tr>
            @endforeach
        </tbody>
    </table>
</div>