# language: en
@machinelearning
Feature: Amazon Machine Learning

  I want to use Amazon Machine Learning

  Scenario: Making a request
    When I call the "DescribeMLModels" API
    Then the value at "Results" should be a list

  Scenario: Error handling
    When I attempt to call the "GetBatchPrediction" API with:
    | BatchPredictionId | fake-id |
    Then the error code should be "ResourceNotFoundException"
    """
    No BatchPrediction with id fake-id exists
    """
