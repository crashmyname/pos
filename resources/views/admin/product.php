    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card-header">
                    <h4 class="card-title"><?= $title?></h4>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-product">
                        Add Product
                    </button>
                    <button class="btn btn-indigo" data-bs-toggle="modal" data-bs-target="#modal-import">
                        Import Product
                    </button>
                    <div id="productContainer" class="w-full overflow-x-auto sm:overflow-visible mt-3">
                        <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden border border-gray-200 rounded-lg">
                            <table id="productTable" class="min-w-full border-collapse text-sm sm:text-base">
                            <thead></thead>
                            <tbody></tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-product" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formaddproduct" action="" enctype="multipart/form-data" method="post">
                    <?= csrf()?>
                    <div class="modal-header">
                        <h5 class="modal-title">New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <input type="text" class="form-control" name="product" id="product">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select class="form-control" id="supplier_id" name="supplier_id">
                                <?php foreach($supplier as $supp):?>
                                    <option value="<?= $supp->id?>"><?=  $supp->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <?php foreach($category as $ctg):?>
                                    <option value="<?= $ctg->id?>"><?=  $ctg->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">QR/Bar Code</label>
                            <input type="text" class="form-control" name="qr_code" id="qr_code">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Buy Price</label>
                            <input type="text" class="form-control" name="buy_price" id="buy_price">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sell Price</label>
                            <input type="text" class="form-control" name="sell_price" id="sell_price">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" id="image">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Is Active</label>
                            <select class="form-control" id="is_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="addproduct" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create new product
                        </button>
                        <button class="btn btn-primary ms-auto" style="display: none;" id="loading" disabled>
                            <div class="spinner-border me-2" role="status"></div>
                            <strong>Loading...</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formeditproduct" action="" enctype="multipart/form-data" method="post">
                    <?= method('PUT')?>
                    <div class="modal-header">
                        <h5 class="modal-title">Update Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" class="form-control" name="id" id="pid">
                            <label class="form-label">Product</label>
                            <input type="text" class="form-control" name="product" id="uproduct">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select class="form-control" id="usupplier_id" name="supplier_id">
                                <?php foreach($supplier as $supp):?>
                                    <option value="<?= $supp->id?>"><?=  $supp->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-control" id="ucategory_id" name="category_id">
                                <?php foreach($category as $ctg):?>
                                    <option value="<?= $ctg->id?>"><?=  $ctg->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">QR/Bar Code</label>
                            <input type="text" class="form-control" name="qr_code" id="uqr_code">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="udescription" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Buy Price</label>
                            <input type="text" class="form-control" name="buy_price" id="ubuy_price">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sell Price</label>
                            <input type="text" class="form-control" name="sell_price" id="usell_price">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" id="uimage">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Is Active</label>
                            <select class="form-control" id="uis_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="editproduct" class="btn btn-yellow ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Update product
                        </button>
                        <button class="btn btn-yellow ms-auto" style="display: none;" id="loadingedit" disabled>
                            <div class="spinner-border me-2" role="status"></div>
                            <strong>Loading...</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-import" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formimportproduct" action="" enctype="multipart/form-data" method="post">
                    <?= csrf()?>
                    <div class="modal-header">
                        <h5 class="modal-title">Form Import Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">File</label>
                            <input type="file" class="form-control" name="file" id="file">
                        </div>
                    </div>
                    <div class="container container-slim py-2" id="loadingbar" style="display:none">
                        <div class="text-center">
                        <div class="text-secondary mb-1">Waiting....</div>
                            <div class="progress progress-sm">
                                <div class="progress-bar progress-bar-indeterminate"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="importproduct" class="btn btn-indigo ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Import
                        </button>
                        <button id="loadingimport" disabled class="btn btn-indigo ms-auto" style="display:none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            <div class="spinner-border me-2" role="status"></div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= asset_v('js/table-plus.js')?>"></script>
    <script>
        var csrfToken = '<?= csrfHeader()?>'
        var createProduct = '<?= route('create.admin.product')?>'
        var getProduct = '<?= route('data.admin.product')?>'
        var editProduct = '<?= url('admin/product')?>'
        var deleteProduct = '<?= url('admin/product')?>'
        var urlImport = '<?= route('import.admin.product')?>'
    </script>
    <script src="<?= asset_v('js/product.js')?>"></script>