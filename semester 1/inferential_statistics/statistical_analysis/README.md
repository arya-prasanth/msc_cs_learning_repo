# Inferential Statistics – Component 02

## Statistical Analysis of Student Mental Health Data Using R

This project focuses on applying inferential statistical techniques to a Student Mental Health dataset obtained from Kaggle. The analysis is performed using **R and RStudio** to investigate depression proportions, age differences, and the relationship between age and CGPA.

## Project Overview

The project uses a survey dataset containing 101 student responses and 11 variables. Statistical tests and visualisations are used to analyse the data and interpret the results.

## Dataset Information

- **Dataset:** Student Mental Health
- **Contributor:** Md Shariful Islam
- **Source:** [Kaggle – Student Mental Health Dataset](https://www.kaggle.com/datasets/shariful07/student-mental-health)
- **Observations:** 101
- **Variables:** 11
- **Tools:** R, RStudio

The dataset includes information about students' age, gender, course, CGPA, depression, anxiety, panic attacks, and specialist treatment.

## Statistical Techniques

### 1. One-Sample Proportion Z-Test
Tests whether the proportion of students reporting depression differs significantly from 50%.

**Result:**
- Sample proportion: 0.3465
- Z statistic: -3.0846
- P-value: 0.0020
- **Conclusion:** Reject the Null Hypothesis.

### 2. Independent Two-Sample Welch T-Test
Compares the mean age of students reporting depression with those not reporting depression.

**Result:**
- Mean age (Depression = Yes): 20.28571
- Mean age (Depression = No): 20.66154
- T statistic: -0.73062
- P-value: 0.4673
- **Conclusion:** Fail to Reject the Null Hypothesis.

### 3. Simple Linear Regression
Examines the linear relationship between age and approximate CGPA using CGPA-range midpoints.

**Result:**
- Intercept: 3.4044836
- Age coefficient: 0.0002565
- R-squared: 0.000002353
- P-value: 0.9879
- **Conclusion:** No statistically significant linear relationship was found.

### 4. Pearson Correlation
Measures the strength and direction of the linear correlation between age and approximate CGPA.

**Result:**
- Pearson correlation coefficient: 0.001533956
- P-value: 0.9879
- **Conclusion:** Negligible positive correlation with no statistical significance.

## Project Structure

```text
inferential_stat/
│
├── README.md
├── inferential_statistics.R
│
├── data/
│   └── Student mental health.csv
│
└── plots/
    ├── regression_plot.png
    └── correlation_plot.png
```

## How to Run

1. Clone or download this repository.
2. Download the Student Mental Health dataset from Kaggle.
3. Place the CSV file inside the `data` folder and name it `Student mental health.csv`.
4. Open `inferential_statistics.R` in RStudio.
5. Set the working directory to the `inferential_stat` folder.
6. Run the script.

If the CSV is not found in the expected location, the script will open a file chooser.

The generated plots will be saved automatically in the `plots` folder.

## Overall Conclusion

The analysis identified a statistically significant difference between the observed proportion of students reporting depression and the hypothesised proportion of 50%.

However, the Welch t-test did not identify a statistically significant difference in mean age between the two depression groups. Regression and Pearson correlation also indicated no statistically significant linear relationship between age and approximate CGPA.

## Limitations

- The dataset contains only 101 survey responses.
- One age value is missing.
- CGPA is recorded in ranges rather than exact numerical values.
- CGPA midpoints are used as approximations.
- The results cannot automatically be generalised to all students.
- Statistical association does not establish causation.

## References

Md Shariful Islam. *Student Mental Health Dataset*. Kaggle.

https://www.kaggle.com/datasets/shariful07/student-mental-health

**Software:** R and RStudio

---

*Academic project – Inferential Statistics, Component 02.*
