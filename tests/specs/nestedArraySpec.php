<?php

use Haijin\Validations\Validator;

$spec->describe("When validating nested attributes in validations of indexed arrays", function () {

    $this->def("incrementCalledCounter", function () {

        $this->calledCounter += 1;

    });

    $this->it("nested array passes", function () {

        $purchase = [
            'items' => [
                [
                    'price' => 1.00
                ],
                [
                    'price' => 2.0
                ],
                [
                    'price' => 3.00
                ]
            ],
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($purchase, function ($purchase) {
            $purchase->isPresent();

            $purchase->attr('items', function ($items) {
                $items->isPresent()->isArray()->notEmpty();

                $items->each(function ($eachItem) {
                    $eachItem->attr('price', function ($street) {
                        $street->isPresent()->isFloat();
                    });
                });
            });
        });

        $this->expect(count($validationErrors))->to()->equal(0);

    });


    $this->it("nested array fails", function () {

        $purchase = [
            'items' => [
                [
                    'price' => 1.00
                ],
                [
                    'price' => null
                ],
                [
                    'price' => 3.00
                ]
            ],
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($purchase, function ($purchase) {
            $purchase->isPresent();

            $purchase->attr('items', function ($items) {
                $items->isPresent()->isArray()->notEmpty();

                $items->each(function ($eachItem) {
                    $eachItem->attr('price', function ($street) {
                        $street->isPresent()->isFloat();
                    });
                });
            });
        });

        $this->expect(count($validationErrors))->to()->equal(1);

        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => null,
            'getAttributePath()' => 'items.[1].price',
            'getValidationName()' => 'isPresent',
            'getValidationParameters()' => []
        ]);

    });


    $this->it("this binding is kept across nested attribute validations", function () {

        $purchase = [
            'items' => [
                [
                    'price' => 1.00
                ],
                [
                    'price' => 2.0
                ],
                [
                    'price' => 3.00
                ]
            ],
        ];

        $this->calledCounter = 0;

        $validator = new Validator();

        $validationErrors = $validator->validate($purchase, function ($purchase) {
            $this->incrementCalledCounter();

            $purchase->isPresent();

            $purchase->attr('items', function ($items) {
                $this->incrementCalledCounter();

                $items->isPresent()->isArray()->notEmpty();

                $items->each(function ($eachItem) {
                    $this->incrementCalledCounter();

                    $eachItem->attr('price', function ($street) {
                        $this->incrementCalledCounter();

                        $street->isPresent()->isFloat();
                    });
                });
            });
        });

        $this->expect($this->calledCounter)->to()->equal(8);

    });


    $this->it("sibling items are validated even when an item validation fails", function () {

        $purchase = [
            'items' => [
                [
                    'price' => 1.00
                ],
                [
                    'price' => null
                ],
                [
                    'price' => 3.00
                ]
            ],
        ];

        $this->calledCounter = 0;

        $validator = new Validator();

        $validationErrors = $validator->validate($purchase, function ($purchase) {
            $purchase->isPresent();

            $purchase->attr('items', function ($items) {
                $items->isPresent()->isArray()->notEmpty();

                $items->each(function ($eachItem) {
                    $eachItem->isPresent();

                    $eachItem->attr('price', function ($street) {
                        $this->incrementCalledCounter();

                        $street->isPresent()->isFloat();
                    });
                });
            });
        });

        $this->expect($this->calledCounter)->to()->equal(3);

        $this->expect(count($validationErrors))->to()->equal(1);

        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => null,
            'getAttributePath()' => 'items.[1].price',
            'getValidationName()' => 'isPresent',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("values are kept when nested attributes assigning values passes", function () {

        $purchase = [
            'items' => [
                [
                    'price' => '1.01'
                ],
                [
                    'price' => '2.01'
                ],
                [
                    'price' => '3.01'
                ]
            ],
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($purchase, function ($purchase) {
            $purchase->isPresent();

            $purchase->attr('items', function ($items) {
                $items->isPresent()->isArray()->notEmpty();

                $items->each(function ($eachItem) {
                    $eachItem->attr('price', function ($street) {
                        $street->isPresent()->asFloat();
                    });
                });
            });
        });

        $this->expect($validator->getValue())->to()->be()->exactlyLike([
            'items' => [
                [
                    'price' => function ($value) {
                        $this->expect($value)->to()->be("===")->than(1.01);
                    }
                ],
                [
                    'price' => function ($value) {
                        $this->expect($value)->to()->be("===")->than(2.01);
                    }
                ], [
                    'price' => function ($value) {
                        $this->expect($value)->to()->be("===")->than(3.01);
                    }
                ]
            ]
        ]);

    });

});