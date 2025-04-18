<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Progress</title>
    <style>
        .container {
            max-height: 219px;
            overflow-y: auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 0 40px;
            height: 390px;
        }

        .box {
            border: 1px solid black;
            border-radius: 20px;
            height: 120px;
            width: 418px;
            margin: 10px 5px;
            box-shadow: 5px 7px 2px 2px rgba(0, 0, 0, 0.1);
            -webkit-box-shadow: 5px 7px 2px 2px rgba(0, 0, 0, 0.1);
            -moz-box-shadow: 5px 7px 2px 2px rgba(0, 0, 0, 0.1);
        }

        .top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 20px;
            margin: 8px 10px;
            font-size: 14px;
            font-weight: 500;
        }

        .circle {
            position: relative;
            border: 1px solid #1f1e1e;
            border-radius: 24px;
            height: 15px;
            margin: 20px 10px 0;
        }

        .inner-circle {
            position: absolute;
            background-color: #00ff99;
            border: 1px solid #1f1e1e;
            border-radius: 24px;
            height: 15px;
            width: 90px;
        }

        .bottom {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin: 5px 15px;
        }

        .budget {
            margin: 60px 20px 0 20px;
            font-size: 14px;
        }

        .budget-header {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="container" id="budget-container">
        <!-- Categories will be dynamically added here by JavaScript -->
    </div>

    <script>
        // Sample data for categories, amounts, spent, and remaining
        const budgetCategories = [{
                name: "Food",
                amount: 1000,
                spent: 400
            },
            {
                name: "Transport",
                amount: 800,
                spent: 600
            },
            {
                name: "Entertainment",
                amount: 500,
                spent: 200
            },
            {
                name: "Savings",
                amount: 1500,
                spent: 1000
            }
        ];

        // Function to calculate the progress percentage
        function calculateProgress(spent, total) {
            return (spent / total) * 100;
        }

        // Function to create a budget category box
        function createCategoryBox(category) {
            const box = document.createElement("div");
            box.classList.add("box");

            // Top section
            const top = document.createElement("div");
            top.classList.add("top");

            const categoryName = document.createElement("div");
            categoryName.style.fontWeight = "700";
            categoryName.textContent = category.name;

            const amount = document.createElement("div");
            amount.innerHTML = `₱${category.amount} <label>Budgeted</label>`;

            top.appendChild(categoryName);
            top.appendChild(amount);

            // Circle with progress bar
            const circle = document.createElement("div");
            circle.classList.add("circle");

            const innerCircle = document.createElement("div");
            innerCircle.classList.add("inner-circle");
            innerCircle.style.width = `${calculateProgress(category.spent, category.amount)}%`;

            circle.appendChild(innerCircle);

            // Bottom section
            const bottom = document.createElement("div");
            bottom.classList.add("bottom");

            const spentExpense = document.createElement("div");
            spentExpense.textContent = `₱${category.spent}`;

            const remainingExpense = document.createElement("div");
            const remaining = category.amount - category.spent;
            remainingExpense.textContent = `₱${remaining}`;

            bottom.appendChild(spentExpense);
            bottom.appendChild(remainingExpense);

            // Append top, circle, and bottom to the box
            box.appendChild(top);
            box.appendChild(circle);
            box.appendChild(bottom);

            return box;
        }

        // Add all categories to the container
        const container = document.getElementById("budget-container");
        budgetCategories.forEach(category => {
            container.appendChild(createCategoryBox(category));
        });
    </script>

</body>

</html>